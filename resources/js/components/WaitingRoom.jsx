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
    Logout as LogoutIcon
} from '@mui/icons-material';

const WaitingRoom = ({ consultation, userType, otherParticipantPresent, onCancel }) => {
    const [elapsedTime, setElapsedTime] = useState(0);

    useEffect(() => {
        const timer = setInterval(() => {
            setElapsedTime(prev => prev + 1);
        }, 1000);
        return () => clearInterval(timer);
    }, []);

    const formatTime = (seconds) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    };

    const isDoctor = userType === 'doctor';
    const otherRole = isDoctor ? 'Student' : 'Doctor';
    const otherName = isDoctor ? consultation.student.user.name : consultation.doctor.name;

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
                    backgroundColor: 'rgba(59, 130, 246, 0.2)', // Blue
                    filter: 'blur(100px)',
                    borderRadius: '50%',
                    animation: 'pulse 4s infinite',
                }}
            />
            <Box
                sx={{
                    position: 'absolute',
                    bottom: '20%',
                    right: '20%',
                    width: '300px',
                    height: '300px',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)', // Emerald
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
                        Please wait while we connect you with {otherName}.
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
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderRadius: 3
                            }}
                        >
                            <Box sx={{ display: 'flex', alignItems: 'center', gap: 2 }}>
                                <Avatar sx={{ bgcolor: 'rgba(16, 185, 129, 0.2)', color: '#10b981' }}>
                                    <UsersIcon />
                                </Avatar>
                                <Box sx={{ textAlign: 'left' }}>
                                    <Typography variant="subtitle2" sx={{ color: 'text.secondary' }}>
                                        Your Status
                                    </Typography>
                                    <Typography variant="body1" sx={{ color: 'white', fontWeight: 600 }}>
                                        Connected
                                    </Typography>
                                </Box>
                            </Box>
                            <Chip
                                label="Ready"
                                size="small"
                                sx={{
                                    bgcolor: 'rgba(16, 185, 129, 0.2)',
                                    color: '#10b981',
                                    fontWeight: 600
                                }}
                            />
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
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderRadius: 3
                            }}
                        >
                            <Box sx={{ display: 'flex', alignItems: 'center', gap: 2 }}>
                                <Avatar
                                    sx={{
                                        bgcolor: otherParticipantPresent ? 'rgba(16, 185, 129, 0.2)' : 'rgba(148, 163, 184, 0.2)',
                                        color: otherParticipantPresent ? '#10b981' : '#94a3b8'
                                    }}
                                >
                                    <UsersIcon />
                                </Avatar>
                                <Box sx={{ textAlign: 'left' }}>
                                    <Typography variant="subtitle2" sx={{ color: 'text.secondary' }}>
                                        {otherRole} Status
                                    </Typography>
                                    <Typography variant="body1" sx={{ color: 'white', fontWeight: 600 }}>
                                        {otherParticipantPresent ? 'Connected' : 'Waiting to join...'}
                                    </Typography>
                                </Box>
                            </Box>
                            {otherParticipantPresent ? (
                                <Chip
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
