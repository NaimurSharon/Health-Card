import React, { useEffect, useState } from 'react';
import { 
    StreamVideo, 
    StreamCall, 
    SpeakerLayout, 
    CallControls,
    useCallStateHooks,
    CallingState,
    StreamVideoClient
} from '@stream-io/video-react-sdk';
import CallHeader from './CallHeader';
import PatientSidebar from './PatientSidebar';
import LoadingSpinner from './LoadingSpinner';
import ErrorDisplay from './ErrorDisplay';
import { endCall } from '../services/api';

const CallTimer = () => {
    const { useCallDuration } = useCallStateHooks();
    const duration = useCallDuration();
    
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
    
    // Track if we've already handled the leave event
    const [callEnded, setCallEnded] = useState(false);

    // create Stream client when streamConfig is available
    useEffect(() => {
        if (!streamConfig) return;

        const initializeClient = async () => {
            try {
                console.log('Initializing StreamVideoClient with config:', streamConfig);
                
                // Create client first
                const c = new StreamVideoClient({ 
                    apiKey: streamConfig.apiKey 
                });
                
                // Then connect the user with token
                console.log('Connecting user to Stream Video...');
                await c.connectUser(
                    streamConfig.user,
                    streamConfig.token
                );
                
                console.log('User connected successfully');
                setClient(c);
            } catch (err) {
                console.error('Failed to initialize StreamVideoClient:', err);
                setError(`Failed to initialize video client: ${err.message}`);
                setLoading(false);
            }
        };

        initializeClient();

        return () => {
            // Cleanup on unmount
            if (client) {
                client.disconnectUser().catch(err => console.warn('Failed to disconnect:', err));
            }
        };
    }, [streamConfig]);

    useEffect(() => {
        let mounted = true;

        const initializeCall = async () => {
            try {
                if (!client) return;

                const callInstance = client.call('default', streamConfig.callId);
                setCall(callInstance);

                console.log('Joining call with ID:', streamConfig.callId);
                await callInstance.join({ create: true });

                // After join, notify backend of participant join
                const sessionId = (callInstance.localParticipant && callInstance.localParticipant.sessionId) || callInstance.sessionId || null;
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
                    } catch (err) {
                        console.warn('Failed to POST participantJoined:', err);
                    }

                    // start polling participants count
                    try {
                        const fetchParticipants = async () => {
                            const resp = await fetch(`/video-consultations/${consultation.id}/participants`, {
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                credentials: 'same-origin'
                            });
                            if (resp.ok) {
                                const j = await resp.json();
                                setParticipantsCount(j.count || 0);
                            }
                        };
                        await fetchParticipants();
                        const interval = setInterval(fetchParticipants, 5000);
                        // store on call instance so cleanup can clear it
                        callInstance.__participantsInterval = interval;
                        // start heartbeat every 10 seconds to keep server last_seen updated
                        // Heartbeat with exponential backoff on failures
                        const startHeartbeat = () => {
                            if (callInstance.__heartbeatTimer) return;
                            // initial delay 10s
                            callInstance.__heartbeatDelay = callInstance.__heartbeatDelay || 10000;
                            callInstance.__heartbeatFailures = callInstance.__heartbeatFailures || 0;

                            const sendHeartbeat = async () => {
                                try {
                                    const resp = await fetch(`/video-consultations/${consultation.id}/heartbeat`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrf,
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        credentials: 'same-origin',
                                        body: JSON.stringify({ participant: { sessionId: sessionId, user: { id: streamConfig.user.id } } })
                                    });

                                    if (!resp.ok) throw new Error('Heartbeat failed');

                                    // success -> reset failures and delay
                                    callInstance.__heartbeatFailures = 0;
                                    callInstance.__heartbeatDelay = 10000;
                                } catch (err) {
                                    // failure -> increase failure count and backoff
                                    callInstance.__heartbeatFailures = (callInstance.__heartbeatFailures || 0) + 1;
                                    callInstance.__heartbeatDelay = Math.min((callInstance.__heartbeatDelay || 10000) * 2, 60000);
                                } finally {
                                    // schedule next heartbeat unless stopped
                                    try {
                                        callInstance.__heartbeatTimer = setTimeout(sendHeartbeat, callInstance.__heartbeatDelay || 10000);
                                    } catch (e) {}
                                }
                            };

                            // start first send immediately
                            callInstance.__heartbeatTimer = setTimeout(sendHeartbeat, 0);
                        };

                        const stopHeartbeat = () => {
                            try {
                                if (callInstance.__heartbeatTimer) {
                                    clearTimeout(callInstance.__heartbeatTimer);
                                    callInstance.__heartbeatTimer = null;
                                }
                            } catch (e) {}
                        };

                        // Start heartbeat now
                        startHeartbeat();

                        // Pause heartbeats when tab is hidden to save requests, resume when visible
                        const visibilityHandler = () => {
                            if (document.hidden) {
                                stopHeartbeat();
                            } else {
                                startHeartbeat();
                            }
                        };

                        document.addEventListener('visibilitychange', visibilityHandler);
                        callInstance.__visibilityHandler = visibilityHandler;
                    } catch (err) {
                        console.warn('Failed to start participants polling:', err);
                    }
                }

                if (mounted) setLoading(false);

            } catch (err) {
                console.error('Failed to initialize call:', err);
                if (mounted) {
                    setError(err.message);
                    setLoading(false);
                }
            }
        };

        if (client) {
            initializeCall();
        }

        return () => {
            mounted = false;
            if (call) {
                // notify server of leaving
                const sessionId = (call.localParticipant && call.localParticipant.sessionId) || call.sessionId || null;
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                if (sessionId) {
                    fetch(`/video-consultations/${consultation.id}/left`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ participant: { sessionId: sessionId } })
                    }).catch(console.warn);
                }

                // clear participants polling interval and heartbeat if set
                try {
                    const interval = call.__participantsInterval;
                    if (interval) clearInterval(interval);
                } catch (e) {}
                try {
                    if (call.__heartbeatTimer) {
                        clearTimeout(call.__heartbeatTimer);
                        call.__heartbeatTimer = null;
                    }
                } catch (e) {}

                // remove visibility handler if attached
                try {
                    if (call.__visibilityHandler) {
                        document.removeEventListener('visibilitychange', call.__visibilityHandler);
                        call.__visibilityHandler = null;
                    }
                } catch (e) {}

                call.leave().catch(console.error);
            }
        };
    }, [client, streamConfig.callId]);

    const handleEndCall = async () => {
        try {
            if (call) {
                await call.leave();
            }
            
            // Notify backend
            await endCall(consultation.id);
            
            // Redirect based on user type
            const redirectUrl = userType === 'doctor' 
                ? `/doctor/consultations/${consultation.id}`
                : '/student/consultations';
                
            window.location.href = redirectUrl;
        } catch (err) {
            console.error('Error ending call:', err);
        }
    };

    // Monitor call state for when user clicks leave button
    useEffect(() => {
        if (!call || callEnded) return;

        // Listen for call.left event from Stream SDK
        const handleCallLeft = () => {
            console.log('Call left via CallControls');
            if (!callEnded) {
                setCallEnded(true);
                handleEndCall();
            }
        };

        // Also handle when call ends
        const handleCallEnded = () => {
            console.log('Call ended');
            if (!callEnded) {
                setCallEnded(true);
                handleEndCall();
            }
        };

        try {
            call.on('call.left', handleCallLeft);
            call.on('call.ended', handleCallEnded);
        } catch (e) {
            console.warn('Could not attach call event listeners:', e);
        }

        return () => {
            try {
                call.off('call.left', handleCallLeft);
                call.off('call.ended', handleCallEnded);
            } catch (e) {}
        };
    }, [call, callEnded]);

    if (loading) {
        return <LoadingSpinner message="Joining video call..." />;
    }

    if (error) {
        return <ErrorDisplay message={error} onRetry={() => window.location.reload()} />;
    }

    if (!client || !call) {
        return null;
    }

    return (
        <div className="h-screen flex flex-col bg-gray-900">
            <CallHeader 
                consultation={consultation}
                userType={userType}
                onEndCall={handleEndCall}
                onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                sidebarOpen={sidebarOpen}
            />
            
            <div className="flex-1 flex">
                <div className={`flex-1 relative ${sidebarOpen ? 'lg:mr-80' : ''}`}>
                    <StreamVideo client={client}>
                        <StreamCall call={call}>
                            <div className="h-full w-full relative">
                                <SpeakerLayout />
                                
                                {/* Call Controls */}
                                <div className="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-20">
                                    <div onClick={(e) => e.stopPropagation()}>
                                        <CallControls />
                                    </div>
                                </div>
                                
                                {/* Call Info */}
                                <div className="absolute top-4 left-4 bg-black bg-opacity-60 text-white px-4 py-2 rounded-lg z-20">
                                    <div className="text-sm">
                                        <div className="font-semibold">
                                            {userType === 'student' 
                                                ? `Dr. ${consultation.doctor.name}`
                                                : consultation.student.user.name
                                            }
                                        </div>
                                        <div className="text-xs text-gray-300">
                                                    <CallTimer /> • <span>{participantsCount} participant{participantsCount !== 1 ? 's' : ''}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </StreamCall>
                    </StreamVideo>
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
    );
};

export default VideoCall;