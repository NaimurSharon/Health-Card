import React, { useEffect, useState, useRef, useCallback } from 'react';
import {
    StreamVideo,
    StreamCall,
    StreamVideoClient,
    useCallStateHooks
} from '@stream-io/video-react-sdk';
import { Box, Typography } from '@mui/material';
import CallHeader from './CallHeader';
import PatientSidebar from './PatientSidebar';
import LoadingSpinner from './LoadingSpinner';
import ErrorDisplay from './ErrorDisplay';
import CustomCallControls from './CustomCallControls';
import CustomVideoLayout from './CustomVideoLayout';
import SessionTimer, { useParticipantDisconnectionMonitor } from './SessionTimer';
import WaitingRoom from './WaitingRoom';
import { endCall } from '../services/api';

const VideoCall = ({ streamConfig, consultation, userType }) => {
    const [client, setClient] = useState(null);
    const [call, setCall] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [showWaitingRoom, setShowWaitingRoom] = useState(true);
    const [bothReady, setBothReady] = useState(false);

    // Track if we've already left the call to prevent duplicate leave attempts
    const callLeftRef = useRef(false);
    const isMountedRef = useRef(true);

    // Handle session timeout
    const handleSessionTimeout = useCallback(async () => {
        console.log('Session timeout - 15 minutes expired');
        await handleEndCall();
    }, []);

    // Handle participant disconnection (2 minutes offline)
    const handleParticipantDisconnection = useCallback(async (participant) => {
        console.log('Participant disconnected for 2 minutes:', participant.name);
        await handleEndCall();
    }, []);

    // Monitor participant disconnections (only apply after call is initialized)
    useParticipantDisconnectionMonitor(handleParticipantDisconnection);

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
            if (client) {
                client.disconnectUser().catch(err => console.warn('Failed to disconnect:', err));
            }
        };
    }, [streamConfig]);

    // Join call when client is ready
    useEffect(() => {
        let mounted = true;

        const initializeCall = async () => {
            try {
                if (!client) return;

                const callInstance = client.call('default', streamConfig.callId);
                setCall(callInstance);

                console.log('Joining call with ID:', streamConfig.callId);
                await callInstance.join({ create: true });

                if (mounted) {
                    setLoading(false);
                }

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
            // Don't leave here - let handleEndCall or beforeunload handle it
            // This prevents duplicate leave() calls
        };
    }, [client, streamConfig.callId]);

    const handleEndCall = async () => {
        try {
            console.log('End call initiated');
            setLoading(true);

            // Leave Stream Video call
            if (call && !callLeftRef.current) {
                console.log('Leaving Stream Video call...');
                try {
                    callLeftRef.current = true;
                    await call.leave();
                    console.log('Successfully left Stream Video call');
                } catch (err) {
                    if (!err.message?.includes('already been left')) {
                        console.error('Error leaving Stream call:', err);
                    }
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

                if (response.redirect_url) {
                    redirectUrl = response.redirect_url;
                }
            } catch (err) {
                console.error('Backend notification error:', err);
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

    // Handle page unload
    useEffect(() => {
        const handleBeforeUnload = async () => {
            if (!call) return;

            try {
                console.log('Page unloading - cleaning up call...');

                if (navigator.sendBeacon) {
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    navigator.sendBeacon(
                        `/api/video-call/${consultation.id}/end`,
                        JSON.stringify({ csrf_token: csrf })
                    );
                }

                if (!callLeftRef.current) {
                    callLeftRef.current = true;
                    await call.leave().catch(() => { });
                }
                if (client) {
                    await client.disconnectUser().catch(() => { });
                }
            } catch (err) {
                console.warn('Unload cleanup error:', err);
            }
        };

        window.addEventListener('beforeunload', handleBeforeUnload);
        return () => {
            window.removeEventListener('beforeunload', handleBeforeUnload);
        };
    }, [call, client, consultation]);

    // Handle when both users are ready
    const handleBothReady = useCallback(() => {
        console.log('Both users ready - hiding waiting room');
        setBothReady(true);
        setShowWaitingRoom(false);
    }, []);

    // Handle cancel from waiting room
    const handleCancelWaiting = () => {
        window.location.href = userType === 'doctor'
            ? `/doctor/consultations/${consultation.id}`
            : `/video-consultations/${consultation.id}`;
    };

    // Show waiting room if enabled and not both ready
    if (showWaitingRoom && !bothReady) {
        return (
            <WaitingRoom
                consultationId={consultation.id}
                userType={userType}
                onBothReady={handleBothReady}
                onCancel={handleCancelWaiting}
            />
        );
    }

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
        <Box
            sx={{
                position: 'fixed',
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                width: '100vw',
                height: '100vh',
                bgcolor: '#000',
                overflow: 'hidden',
                margin: 0,
                padding: 0
            }}
        >
            {/* Header */}
            <CallHeader
                consultation={consultation}
                userType={userType}
                onEndCall={handleEndCall}
                onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
                sidebarOpen={sidebarOpen}
            />

            {/* Video Area - Full Screen */}
            <Box
                sx={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    right: sidebarOpen ? { xs: 0, lg: '320px' } : 0,
                    bottom: 0,
                    width: sidebarOpen ? { xs: '100%', lg: 'calc(100% - 320px)' } : '100%',
                    height: '100%',
                    transition: 'right 0.3s, width 0.3s'
                }}
            >
                <StreamVideo client={client}>
                    <StreamCall call={call}>
                        <Box sx={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, width: '100%', height: '100%' }}>
                            {/* Full Screen Video Layout */}
                            <CustomVideoLayout />

                            {/* Session Timer with Alerts (15 min limit) */}
                            <SessionTimer onTimeExpired={handleSessionTimeout} />

                            {/* Floating Call Controls */}
                            <Box
                                sx={{
                                    position: 'absolute',
                                    bottom: { xs: 80, sm: 100 },
                                    left: '50%',
                                    transform: 'translateX(-50%)',
                                    zIndex: 30,
                                    width: '100%',
                                    display: 'flex',
                                    justifyContent: 'center',
                                    px: 2
                                }}
                            >
                                <CustomCallControls onEndCall={handleEndCall} />
                            </Box>
                        </Box>
                    </StreamCall>
                </StreamVideo>
            </Box>

            {/* Patient Sidebar (Doctor only) */}
            {userType === 'doctor' && (
                <PatientSidebar
                    consultation={consultation}
                    isOpen={sidebarOpen}
                    onClose={() => setSidebarOpen(false)}
                />
            )}
        </Box>
    );
};

export default VideoCall;
