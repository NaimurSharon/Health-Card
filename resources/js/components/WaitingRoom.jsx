import React, { useEffect, useState } from 'react';
import {
    Box,
    Typography,
    Paper,
    Avatar,
    Button,
    CircularProgress,
    Container,
    Stack,
    Chip
} from '@mui/material';
import {
    Videocam as VideoIcon,
    Group as UsersIcon,
    Schedule as ClockIcon,
    Logout as LogoutIcon,
    CheckCircle
} from '@mui/icons-material';

const WaitingRoom = ({ consultationId, userType, onBothReady, onCancel }) => {
    const [elapsedTime, setElapsedTime] = useState(0);
    const [patientReady, setPatientReady] = useState(false);
    const [doctorReady, setDoctorReady] = useState(false);
    const [checking, setChecking] = useState(true);
    const [error, setError] = useState(null);

    // Timer for elapsed time
    useEffect(() => {
        const timer = setInterval(() => {
            setElapsedTime(prev => prev + 1);
        }, 1000);
        return () => clearInterval(timer);
    }, []);

    // Mark current user as ready on mount
    useEffect(() => {
        const markReady = async () => {
            try {
                const endpoint = userType === 'doctor'
                    ? `/doctor/consultations/${consultationId}/ready`
                    : `/video-consultations/${consultationId}/ready`;

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to mark as ready');
                }

                const data = await response.json();
                console.log('Marked as ready:', data);
                setPatientReady(data.patient_ready);
                setDoctorReady(data.doctor_ready);

                // If both ready immediately, start call
                if (data.both_ready) {
                    console.log('Both ready immediately!');
                    onBothReady();
                }
            } catch (err) {
                console.error('Error marking ready:', err);
                setError('Failed to join waiting room');
            } finally {
                setChecking(false);
            }
        };

        markReady();
    }, [consultationId, userType, onBothReady]);

    // Poll for presence every 2 seconds
    useEffect(() => {
        if (checking) return; // Don't start polling until initial ready is complete

        const checkPresence = async () => {
            try {
                // First, check if call was rejected/ended (for students only)
                if (userType === 'student') {
                    try {
                        const statusResponse = await fetch(`/student/video-consultations/${consultationId}/status`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (statusResponse.ok) {
                            const statusData = await statusResponse.json();

                            // If doctor rejected or ended the call, redirect immediately
                            if (statusData.should_redirect) {
                                console.log('Call rejected/ended by doctor, redirecting...', statusData);

                                if (statusData.message) {
                                    alert(statusData.message);
                                }

                                window.location.href = statusData.redirect_url;
                                return; // Stop further execution
                            }
                        }
                    } catch (statusErr) {
                        console.error('Error checking call status:', statusErr);
                    }
                }

                // Then check presence as normal
                const endpoint = userType === 'doctor'
                    ? `/doctor/consultations/${consultationId}/presence`
                    : `/video-consultations/${consultationId}/presence`;

                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to check presence');
                }

                const data = await response.json();
                setPatientReady(data.patient_present);
                setDoctorReady(data.doctor_present);

                // If both ready, notify parent to start call
                if (data.both_ready) {
                    console.log('Both users ready! Starting call...');
                    onBothReady();
                }
            } catch (err) {
                console.error('Error checking presence:', err);
            }
        };

        // Initial check
        checkPresence();

        // Start polling
        const interval = setInterval(checkPresence, 2000);

        return () => clearInterval(interval);
    }, [consultationId, userType, onBothReady, checking]);

    const formatTime = (seconds) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    };

    const isDoctor = userType === 'doctor';
    const currentUserReady = isDoctor ? doctorReady : patientReady;
    const otherUserReady = isDoctor ? patientReady : doctorReady;
    const otherRole = isDoctor ? 'Patient' : 'Doctor';

    if (error) {
        return (
            <Box
                sx={{
                    position: 'fixed',
                    inset: 0,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    background: 'linear-gradient(135deg, #0f172a 0%, #000000 100%)',
                    p: 2
                }}
            >
                <Container maxWidth="sm">
                    <Paper sx={{ p: 4, textAlign: 'center', bgcolor: 'rgba(30, 41, 59, 0.8)' }}>
                        <Typography variant="h5" color="error" gutterBottom>
                            {error}
                        </Typography>
                        <Typography variant="body2" color="text.secondary" sx={{ mt: 2, mb: 3 }}>
                            Please refresh the page to try again
                        </Typography>
                        <Button variant="contained" onClick={() => window.location.reload()}>
                            Refresh Page
                        </Button>
                    </Paper>
                </Container>
            </Box>
        );
    }

    return (
        <Box
            sx={{
                position: 'fixed',
                inset: 0,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                background: 'linear-gradient(135deg, #0f172a 0%, #000000 100%)',
                zIndex: 1300,
                p: 2
            }}
        >
            {/* Animated Background Blobs */}
            <Box
                sx={{
                    position: 'absolute',
                    top: '20%',
                    left: '20%',
                    width: '300px',
                    height: '300px',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    filter: 'blur(100px)',
                    borderRadius: '50%',
                    animation: 'pulse 4s infinite',
                    '@keyframes pulse': {
                        '0%, 100%': { transform: 'scale(1)', opacity: 0.5 },
                        '50%': { transform: 'scale(1.1)', opacity: 0.8 }
                    }
                }}
            />
            <Box
                sx={{
                    position: 'absolute',
                    bottom: '20%',
                    right: '20%',
                    width: '300px',
                    height: '300px',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    filter: 'blur(100px)',
                    borderRadius: '50%',
                    animation: 'pulse 4s infinite 2s',
                }}
            />

            <Container maxWidth="sm" sx={{ position: 'relative', zIndex: 1 }}>
                <Paper
                    elevation={24}
                    sx={{
                        p: 4,
                        borderRadius: 4,
                        textAlign: 'center',
                        background: 'rgba(30, 41, 59, 0.6)',
                        backdropFilter: 'blur(20px)',
                        border: '1px solid rgba(255, 255, 255, 0.1)',
                    }}
                >
                    {/* Header Icon */}
                    <Box sx={{ display: 'flex', justifyContent: 'center', mb: 3 }}>
                        <Box
                            sx={{
                                position: 'relative',
                                width: 80,
                                height: 80,
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                borderRadius: '50%',
                                background: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                                boxShadow: '0 0 20px rgba(59, 130, 246, 0.5)',
                            }}
                        >
                            <Box
                                sx={{
                                    position: 'absolute',
                                    inset: -4,
                                    borderRadius: '50%',
                                    border: '2px solid rgba(59, 130, 246, 0.3)',
                                    animation: 'ripple 1.5s infinite',
                                    '@keyframes ripple': {
                                        '0%': { transform: 'scale(1)', opacity: 1 },
                                        '100%': { transform: 'scale(1.5)', opacity: 0 },
                                    },
                                }}
                            />
                            <VideoIcon sx={{ fontSize: 40, color: 'white' }} />
                        </Box>
                    </Box>

                    <Typography variant="h4" gutterBottom sx={{ color: 'white', fontWeight: 700 }}>
                        Waiting Room
                    </Typography>

                    <Typography variant="body1" sx={{ color: 'text.secondary', mb: 4 }}>
                        {checking
                            ? 'Connecting to waiting room...'
                            : otherUserReady
                                ? 'Both participants ready! Starting call...'
                                : `Waiting for ${otherRole.toLowerCase()} to join...`
                        }
                    </Typography>

                    <Stack spacing={2} sx={{ mb: 4 }}>
                        {/* Your Status */}
                        <Paper
                            variant="outlined"
                            sx={{
                                p: 2,
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                                background: 'rgba(255, 255, 255, 0.05)',
                                borderColor: currentUserReady ? 'rgba(16, 185, 129, 0.5)' : 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 2,
                                borderRadius: 3,
                                transition: 'all 0.3s ease'
                            }}
                        >
                            <Box sx={{ display: 'flex', alignItems: 'center', gap: 2 }}>
                                <Avatar sx={{
                                    bgcolor: currentUserReady ? 'rgba(16, 185, 129, 0.2)' : 'rgba(148, 163, 184, 0.2)',
                                    color: currentUserReady ? '#10b981' : '#94a3b8'
                                }}>
                                    <UsersIcon />
                                </Avatar>
                                <Box sx={{ textAlign: 'left' }}>
                                    <Typography variant="subtitle2" sx={{ color: 'text.secondary' }}>
                                        Your Status
                                    </Typography>
                                    <Typography variant="body1" sx={{ color: 'white', fontWeight: 600 }}>
                                        {currentUserReady ? 'Connected' : 'Connecting...'}
                                    </Typography>
                                </Box>
                            </Box>
                            {currentUserReady ? (
                                <Chip
                                    icon={<CheckCircle />}
                                    label="Ready"
                                    size="small"
                                    sx={{
                                        bgcolor: 'rgba(16, 185, 129, 0.2)',
                                        color: '#10b981',
                                        fontWeight: 600
                                    }}
                                />
                            ) : (
                                <CircularProgress size={20} sx={{ color: '#94a3b8' }} />
                            )}
                        </Paper>

                        {/* Other Participant Status */}
                        <Paper
                            variant="outlined"
                            sx={{
                                p: 2,
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                                background: 'rgba(255, 255, 255, 0.05)',
                                borderColor: otherUserReady ? 'rgba(16, 185, 129, 0.5)' : 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 2,
                                borderRadius: 3,
                                transition: 'all 0.3s ease'
                            }}
                        >
                            <Box sx={{ display: 'flex', alignItems: 'center', gap: 2 }}>
                                <Avatar
                                    sx={{
                                        bgcolor: otherUserReady ? 'rgba(16, 185, 129, 0.2)' : 'rgba(148, 163, 184, 0.2)',
                                        color: otherUserReady ? '#10b981' : '#94a3b8'
                                    }}
                                >
                                    <UsersIcon />
                                </Avatar>
                                <Box sx={{ textAlign: 'left' }}>
                                    <Typography variant="subtitle2" sx={{ color: 'text.secondary' }}>
                                        {otherRole} Status
                                    </Typography>
                                    <Typography variant="body1" sx={{ color: 'white', fontWeight: 600 }}>
                                        {otherUserReady ? 'Connected' : 'Waiting to join...'}
                                    </Typography>
                                </Box>
                            </Box>
                            {otherUserReady ? (
                                <Chip
                                    icon={<CheckCircle />}
                                    label="Ready"
                                    size="small"
                                    sx={{
                                        bgcolor: 'rgba(16, 185, 129, 0.2)',
                                        color: '#10b981',
                                        fontWeight: 600
                                    }}
                                />
                            ) : (
                                <CircularProgress size={20} sx={{ color: '#94a3b8' }} />
                            )}
                        </Paper>
                    </Stack>

                    {/* Both Ready Indicator */}
                    {currentUserReady && otherUserReady && (
                        <Box
                            sx={{
                                mb: 3,
                                p: 2,
                                bgcolor: 'rgba(16, 185, 129, 0.1)',
                                borderRadius: 2,
                                border: '1px solid rgba(16, 185, 129, 0.3)'
                            }}
                        >
                            <Typography variant="body1" sx={{ color: '#10b981', fontWeight: 600 }}>
                                ✓ Both participants ready! Connecting to call...
                            </Typography>
                        </Box>
                    )}

                    {/* Timer */}
                    <Box
                        sx={{
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            gap: 1,
                            mb: 4,
                            color: 'text.secondary',
                            bgcolor: 'rgba(255, 255, 255, 0.05)',
                            py: 1,
                            px: 2,
                            borderRadius: 10,
                            width: 'fit-content',
                            mx: 'auto'
                        }}
                    >
                        <ClockIcon fontSize="small" />
                        <Typography variant="body2" sx={{ fontFamily: 'monospace' }}>
                            {formatTime(elapsedTime)}
                        </Typography>
                    </Box>

                    {/* Leave Button */}
                    <Button
                        variant="outlined"
                        color="error"
                        startIcon={<LogoutIcon />}
                        onClick={onCancel}
                        fullWidth
                        sx={{
                            borderColor: 'rgba(239, 68, 68, 0.5)',
                            color: '#ef4444',
                            '&:hover': {
                                borderColor: '#ef4444',
                                bgcolor: 'rgba(239, 68, 68, 0.1)'
                            }
                        }}
                    >
                        Leave Waiting Room
                    </Button>
                </Paper>
            </Container>
        </Box>
    );
};

export default WaitingRoom;
