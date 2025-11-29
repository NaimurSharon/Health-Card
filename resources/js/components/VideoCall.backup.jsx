import React, { useEffect, useState, useRef } from 'react';
import { 
    StreamVideo, 
    StreamCall, 
    SpeakerLayout,
    useCallStateHooks,
    CallingState,
    StreamVideoClient
} from '@stream-io/video-react-sdk';
import CallHeader from './CallHeader';
import PatientSidebar from './PatientSidebar';
import LoadingSpinner from './LoadingSpinner';
import ErrorDisplay from './ErrorDisplay';
import CustomCallControls from './CustomCallControls';
import WaitingScreen from './WaitingScreen';
import { endCall } from '../services/api';

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
    const [callStarted, setCallStarted] = useState(false);
    const [remoteParticipantCount, setRemoteParticipantCount] = useState(0);
    const [disconnectTimeout, setDisconnectTimeout] = useState(null);
    const disconnectTimerRef = useRef(null);
    const isMountedRef = useRef(true);

    // create Stream client when streamConfig is available
    useEffect(() => {
        isMountedRef.current = true;
        
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
                if (isMountedRef.current) {
                    setClient(c);
                    setLoading(false);
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
            console.log('End call initiated');
            setLoading(true);
            
            // Leave Stream Video call
            if (call) {
                console.log('Leaving Stream Video call...');
                try {
                    await call.leave();
                    console.log('Successfully left Stream Video call');
                } catch (err) {
                    console.error('Error leaving Stream call:', err);
                }
            }

            // Disconnect Stream Video client
            if (client) {
                console.log('Disconnecting Stream Video client...');
                try {
                    await client.disconnectUser();
                    console.log('Successfully disconnected from Stream');
                } catch (err) {
                    console.error('Error disconnecting from Stream:', err);
                }
            }
            
            // Notify backend that call ended
            let redirectUrl = null;
            try {
                console.log('Notifying backend of call end...');
                const response = await endCall(consultation.id);
                console.log('Backend response:', response);
                
                // Get redirect URL from response
                if (response.redirect_url) {
                    redirectUrl = response.redirect_url;
                }
            } catch (err) {
                console.error('Backend notification error:', err);
                // Fallback redirect if API fails
                redirectUrl = userType === 'doctor' 
                    ? `/doctor/consultations/${consultation.id}`
                    : `/student/video-consultations/${consultation.id}`;
            }
            
            // Redirect to consultation show page
            setTimeout(() => {
                if (redirectUrl) {
                    console.log('Redirecting to:', redirectUrl);
                    window.location.href = redirectUrl;
                } else {
                    // Final fallback
                    console.log('Using fallback redirect');
                    window.location.href = userType === 'doctor' 
                        ? `/doctor/consultations/${consultation.id}`
                        : `/student/video-consultations/${consultation.id}`;
                }
            }, 500);
        } catch (err) {
            console.error('Fatal error ending call:', err);
            setError('Failed to end call properly');
        }
    };

    // Handle page unload to ensure call is properly ended
    useEffect(() => {
        const handleBeforeUnload = async (e) => {
            if (!call) return;

            // Try to cleanly end call
            try {
                console.log('Page unloading - cleaning up call...');
                
                // Use sendBeacon for reliable delivery even on unload
                if (navigator.sendBeacon) {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    navigator.sendBeacon(
                        `/api/video-call/${consultation.id}/end`,
                        JSON.stringify({ csrf_token: csrf })
                    );
                }

                // Also try regular fetch with short timeout
                fetch(`/api/video-call/${consultation.id}/end`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    keepalive: true
                }).catch(() => {});

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
        };
    }, [call, client, consultation, userType]);

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
        <div className="h-screen flex flex-col bg-gradient-to-br from-slate-900 via-slate-950 to-black">
            <CallHeader 
                consultation={consultation}
                userType={userType}
                onEndCall={handleEndCall}
                onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                sidebarOpen={sidebarOpen}
            />
            
            <div className="flex-1 flex overflow-hidden">
                <div className={`flex-1 relative transition-all duration-300 ${sidebarOpen ? 'lg:mr-80' : ''}`}>
                    <StreamVideo client={client}>
                        <StreamCall call={call}>
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