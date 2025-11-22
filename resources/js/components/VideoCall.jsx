import React, { useEffect, useState, useRef } from 'react';
import { 
    StreamVideo, 
    StreamCall, 
    SpeakerLayout,
    StreamVideoClient
} from '@stream-io/video-react-sdk';
import CallHeader from './CallHeader';
import PatientSidebar from './PatientSidebar';
import LoadingSpinner from './LoadingSpinner';
import ErrorDisplay from './ErrorDisplay';
import CustomCallControls from './CustomCallControls';
import WaitingScreen from './WaitingScreen';
import { endCall } from '../services/api';

const DISCONNECT_TIMEOUT = 60000; // 60 seconds = 1 minute

const CallTimer = () => {
    const [duration, setDuration] = useState(0);

    useEffect(() => {
        const interval = setInterval(() => {
            setDuration(prev => prev + 1);
        }, 1000);

        return () => clearInterval(interval);
    }, []);
    
    const formatTime = (seconds) => {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        if (hours > 0) {
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    };

    return <span>{formatTime(duration)}</span>;
};

const VideoCall = ({ streamConfig, consultation, userType }) => {
    const [client, setClient] = useState(null);
    const [call, setCall] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [participantsCount, setParticipantsCount] = useState(0);
    const [remoteParticipantCount, setRemoteParticipantCount] = useState(0);
    const [callStarted, setCallStarted] = useState(false);
    const [waitingForParticipant, setWaitingForParticipant] = useState(true);
    const [participantReturnCountdown, setParticipantReturnCountdown] = useState(null);
    
    const disconnectTimerRef = useRef(null);
    const isMountedRef = useRef(true);
    const hasJoinedRef = useRef(false);

    // Initialize Stream client
    useEffect(() => {
        isMountedRef.current = true;
        
        if (!streamConfig) return;

        const initializeClient = async () => {
            try {
                console.log('Initializing StreamVideoClient...');
                
                const c = new StreamVideoClient({ 
                    apiKey: streamConfig.apiKey 
                });
                
                await c.connectUser(
                    streamConfig.user,
                    streamConfig.token
                );
                
                console.log('User connected successfully');
                if (isMountedRef.current) {
                    setClient(c);
                }
            } catch (err) {
                console.error('Failed to initialize StreamVideoClient:', err);
                if (isMountedRef.current) {
                    setError(`Failed to initialize video client: ${err.message}`);
                    setLoading(false);
                }
            }
        };

        initializeClient();

        return () => {
            isMountedRef.current = false;
        };
    }, [streamConfig]);

    // Initialize call but DON'T join yet (lazy join)
    useEffect(() => {
        if (!client) return;

        const initializeCall = async () => {
            try {
                const callInstance = client.call('default', streamConfig.callId);
                setCall(callInstance);
                console.log('Call instance created:', streamConfig.callId);
                setLoading(false);
            } catch (err) {
                console.error('Failed to initialize call:', err);
                if (isMountedRef.current) {
                    setError(err.message);
                    setLoading(false);
                }
            }
        };

        initializeCall();
    }, [client, streamConfig.callId]);

    // Monitor remote participants and manage disconnect timeout
    useEffect(() => {
        if (!call) return;

        const handleParticipantsChange = async () => {
            const remoteParticipants = call.state.participants.filter(p => !p.isLocalParticipant());
            const remoteCount = remoteParticipants.length;
            
            console.log(`Remote participants: ${remoteCount}`);
            setRemoteParticipantCount(remoteCount);

            // If we have remote participants and haven't joined yet, join the call
            if (remoteCount > 0 && !hasJoinedRef.current) {
                console.log('Remote participant detected, joining call...');
                try {
                    await joinCall();
                } catch (err) {
                    console.error('Failed to join call:', err);
                }
            }

            // If all remote participants left, start disconnect timer
            if (remoteCount === 0 && hasJoinedRef.current && callStarted) {
                console.log('All participants disconnected, starting timeout...');
                startDisconnectTimeout();
            } else if (remoteCount > 0 && disconnectTimerRef.current) {
                // Remote participant returned, clear timeout
                console.log('Participant returned, clearing timeout...');
                clearDisconnectTimeout();
                setParticipantReturnCountdown(null);
            }
        };

        call.state.subscribe(handleParticipantsChange);
        return () => {
            call.state.unsubscribe(handleParticipantsChange);
        };
    }, [call, callStarted]);

    const joinCall = async () => {
        if (!call || hasJoinedRef.current) return;

        try {
            console.log('Joining call...');
            await call.join({ create: true });
            hasJoinedRef.current = true;
            setCallStarted(true);
            setWaitingForParticipant(false);

            // Notify backend of participant join
            const sessionId = call.localParticipant?.sessionId || call.sessionId || null;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            if (sessionId) {
                try {
                    await fetch(`/video-consultations/${consultation.id}/joined`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            participant: {
                                sessionId: sessionId,
                                user: {
                                    id: streamConfig.user.id,
                                    name: streamConfig.user.name,
                                    role: userType
                                }
                            }
                        })
                    });
                    console.log('Participant join notification sent');
                } catch (err) {
                    console.warn('Failed to notify participant join:', err);
                }

                // Start polling participants count
                startParticipantPolling();
                startHeartbeat();
            }

            console.log('Call join complete');
        } catch (err) {
            console.error('Failed to join call:', err);
            if (isMountedRef.current) {
                setError(`Failed to join call: ${err.message}`);
            }
        }
    };

    const startParticipantPolling = () => {
        const fetchParticipants = async () => {
            try {
                const resp = await fetch(`/video-consultations/${consultation.id}/participants`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                });
                if (resp.ok && isMountedRef.current) {
                    const data = await resp.json();
                    setParticipantsCount(data.count || 0);
                }
            } catch (err) {
                console.warn('Failed to fetch participants:', err);
            }
        };

        fetchParticipants();
        const interval = setInterval(fetchParticipants, 5000);
        return interval;
    };

    const startHeartbeat = () => {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const sessionId = call?.localParticipant?.sessionId || call?.sessionId || null;

        const sendHeartbeat = async () => {
            try {
                if (!isMountedRef.current) return;

                const resp = await fetch(`/video-consultations/${consultation.id}/heartbeat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        participant: {
                            sessionId: sessionId,
                            user: { id: streamConfig.user.id }
                        }
                    })
                });

                if (!resp.ok) throw new Error('Heartbeat failed');
            } catch (err) {
                console.warn('Heartbeat error:', err);
            } finally {
                if (isMountedRef.current) {
                    setTimeout(sendHeartbeat, 10000);
                }
            }
        };

        setTimeout(sendHeartbeat, 0);
    };

    const startDisconnectTimeout = () => {
        if (disconnectTimerRef.current) return;

        let remainingTime = DISCONNECT_TIMEOUT / 1000;
        
        const countdownInterval = setInterval(() => {
            remainingTime--;
            if (isMountedRef.current) {
                setParticipantReturnCountdown(remainingTime);
            }

            if (remainingTime <= 0) {
                clearInterval(countdownInterval);
                endCallDueToTimeout();
            }
        }, 1000);

        disconnectTimerRef.current = countdownInterval;
    };

    const clearDisconnectTimeout = () => {
        if (disconnectTimerRef.current) {
            clearInterval(disconnectTimerRef.current);
            disconnectTimerRef.current = null;
        }
    };

    const endCallDueToTimeout = async () => {
        console.log('Ending call due to participant timeout');
        try {
            await handleEndCall();
        } catch (err) {
            console.error('Error ending call:', err);
        }
    };

    const handleEndCall = async () => {
        try {
            console.log('End call initiated');
            setLoading(true);
            
            // Leave Stream Video call
            if (call) {
                try {
                    await call.leave();
                    console.log('Successfully left Stream Video call');
                } catch (err) {
                    console.error('Error leaving Stream call:', err);
                }
            }

            // Disconnect Stream Video client
            if (client) {
                try {
                    await client.disconnectUser();
                    console.log('Successfully disconnected from Stream');
                } catch (err) {
                    console.error('Error disconnecting from Stream:', err);
                }
            }
            
            // Notify backend
            let redirectUrl = null;
            try {
                const response = await endCall(consultation.id);
                if (response.redirect_url) {
                    redirectUrl = response.redirect_url;
                }
            } catch (err) {
                console.error('Backend notification error:', err);
                redirectUrl = userType === 'doctor' 
                    ? `/doctor/consultations/${consultation.id}`
                    : `/student/video-consultations/${consultation.id}`;
            }
            
            // Redirect
            setTimeout(() => {
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            }, 500);
        } catch (err) {
            console.error('Fatal error ending call:', err);
            setError('Failed to end call properly');
        }
    };

    // Handle page unload
    useEffect(() => {
        const handleBeforeUnload = async (e) => {
            if (!call) return;

            try {
                if (navigator.sendBeacon) {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    navigator.sendBeacon(
                        `/api/video-call/${consultation.id}/end`,
                        JSON.stringify({ csrf_token: csrf })
                    );
                }

                await call.leave().catch(() => {});
                if (client) {
                    await client.disconnectUser().catch(() => {});
                }
            } catch (err) {
                console.warn('Unload cleanup error:', err);
            }
        };

        window.addEventListener('beforeunload', handleBeforeUnload);
        return () => {
            window.removeEventListener('beforeunload', handleBeforeUnload);
            clearDisconnectTimeout();
        };
    }, [call, client, consultation, userType]);

    // Loading state
    if (loading) {
        return <LoadingSpinner message="Initializing video call..." />;
    }

    if (error) {
        return <ErrorDisplay message={error} onRetry={() => window.location.reload()} />;
    }

    if (!client || !call) {
        return <LoadingSpinner message="Preparing connection..." />;
    }

    // Waiting for remote participant
    if (waitingForParticipant) {
        return (
            <WaitingScreen 
                consultation={consultation}
                userType={userType}
                onCancel={handleEndCall}
                participantReturnCountdown={participantReturnCountdown}
            />
        );
    }

    return (
        <StreamVideo client={client}>
            <StreamCall call={call}>
                {/* Desktop/Laptop View */}
                <div className="hidden md:flex h-screen w-full flex-col bg-gradient-to-br from-slate-900 via-slate-950 to-black">
                    <CallHeader 
                        consultation={consultation}
                        userType={userType}
                        onEndCall={handleEndCall}
                        onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                        sidebarOpen={sidebarOpen}
                    />
                    
                    <div className="flex-1 flex overflow-hidden">
                        <div className={`flex-1 relative transition-all duration-300 ${sidebarOpen ? 'lg:mr-80' : ''}`}>
                            <div className="h-full w-full relative">
                                <SpeakerLayout />
                                
                                {/* Call Controls */}
                                <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-fadeIn">
                                    <CustomCallControls onEndCall={handleEndCall} />
                                </div>
                                
                                {/* Call Info - Top Left */}
                                <div className="absolute top-6 left-6 bg-gradient-to-br from-slate-900/90 to-slate-800/90 backdrop-blur-xl text-white px-6 py-4 rounded-2xl z-20 border border-slate-700/50 shadow-2xl">
                                    <div className="text-sm space-y-2">
                                        <div className="font-semibold text-lg">
                                            {userType === 'student' 
                                                ? `Dr. ${consultation?.doctor?.name || 'Doctor'}`
                                                : consultation?.student?.user?.name || 'Student'
                                            }
                                        </div>
                                        <div className="flex items-center gap-3 text-xs text-gray-300">
                                            <div className="flex items-center gap-2">
                                                <div className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                <span>Connected</span>
                                            </div>
                                            <span>•</span>
                                            <span><CallTimer /></span>
                                            <span>•</span>
                                            <span>{participantsCount} {participantsCount === 1 ? 'participant' : 'participants'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Patient Sidebar (Doctor only) */}
                        {userType === 'doctor' && (
                            <PatientSidebar 
                                consultation={consultation}
                                isOpen={sidebarOpen}
                                onClose={() => setSidebarOpen(false)}
                            />
                        )}
                    </div>
                </div>

                {/* Mobile View */}
                <div className="md:hidden flex h-screen w-full flex-col bg-gradient-to-br from-slate-900 via-slate-950 to-black">
                    <div className="h-full w-full relative flex flex-col">
                        {/* Video Container - Full Screen */}
                        <div className="flex-1 relative">
                            <SpeakerLayout />
                        </div>
                        
                        {/* Mobile Bottom Controls */}
                        <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/50 to-transparent p-4 space-y-4">
                            {/* Call Info */}
                            <div className="flex items-center justify-between text-white px-4">
                                <div>
                                    <div className="font-semibold">
                                        {userType === 'student' 
                                            ? `Dr. ${consultation?.doctor?.name || 'Doctor'}`
                                            : consultation?.student?.user?.name || 'Student'
                                        }
                                    </div>
                                    <div className="text-xs text-gray-300 flex items-center gap-2">
                                        <span><CallTimer /></span>
                                        <span>•</span>
                                        <span>{participantsCount} {participantsCount === 1 ? 'participant' : 'participants'}</span>
                                    </div>
                                </div>
                                <div className="flex items-center gap-2">
                                    <div className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span className="text-xs">Connected</span>
                                </div>
                            </div>

                            {/* Controls */}
                            <div className="flex justify-center">
                                <CustomCallControls onEndCall={handleEndCall} />
                            </div>
                        </div>
                    </div>
                </div>
            </StreamCall>
        </StreamVideo>
    );
};

export default VideoCall;
