import React from 'react';
import {
    Backdrop,
    Box,
    Typography,
    Button,
    CircularProgress,
    Paper
} from '@mui/material';
import {
    SignalWifiStatusbarConnectedNoInternet4 as DisconnectIcon,
    CallEnd as CallEndIcon
} from '@mui/icons-material';

const ReconnectionOverlay = ({ disconnectedParticipant, timeRemaining, onEndCall }) => {
    // Format time as M:SS
    const formatTime = (seconds) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    };

    return (
        <Backdrop
            sx={{
                color: '#fff',
                zIndex: (theme) => theme.zIndex.drawer + 1,
                backdropFilter: 'blur(8px)',
                backgroundColor: 'rgba(0, 0, 0, 0.8)'
            }}
            open={true}
        >
            <Paper
                elevation={24}
                sx={{
                    p: 4,
                    borderRadius: 4,
                    textAlign: 'center',
                    maxWidth: 400,
                    width: '90%',
                    background: 'rgba(30, 41, 59, 0.8)',
                    backdropFilter: 'blur(16px)',
                    border: '1px solid rgba(239, 68, 68, 0.3)',
                    boxShadow: '0 0 40px rgba(239, 68, 68, 0.2)'
                }}
            >
                <Box
                    sx={{
                        display: 'flex',
                        justifyContent: 'center',
                        mb: 3,
                        position: 'relative'
                    }}
                >
                    <Box
                        sx={{
                            position: 'absolute',
                            inset: -10,
                            borderRadius: '50%',
                            border: '2px solid rgba(239, 68, 68, 0.3)',
                            animation: 'ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite',
                        }}
                    />
                    <Avatar
                        sx={{
                            width: 80,
                            height: 80,
                            bgcolor: 'rgba(239, 68, 68, 0.2)',
                            color: '#ef4444'
                        }}
                    >
                        <DisconnectIcon sx={{ fontSize: 40 }} />
                    </Avatar>
                </Box>

                <Typography variant="h5" gutterBottom sx={{ color: 'white', fontWeight: 700 }}>
                    Reconnecting...
                </Typography>

                <Typography variant="body1" sx={{ color: 'text.secondary', mb: 3 }}>
                    <Box component="span" sx={{ color: 'white', fontWeight: 600 }}>
                        {disconnectedParticipant?.name || 'Participant'}
                    </Box>
                    {' '}has disconnected. Waiting for them to rejoin.
                </Typography>

                <Box
                    sx={{
                        mb: 4,
                        display: 'inline-flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        bgcolor: 'rgba(239, 68, 68, 0.1)',
                        color: '#ef4444',
                        px: 3,
                        py: 1,
                        borderRadius: 10,
                        fontWeight: 'bold',
                        fontSize: '1.5rem',
                        fontFamily: 'monospace'
                    }}
                >
                    {formatTime(timeRemaining)}
                </Box>

                <Button
                    variant="contained"
                    color="error"
                    startIcon={<CallEndIcon />}
                    onClick={onEndCall}
                    fullWidth
                    size="large"
                    sx={{
                        bgcolor: '#ef4444',
                        '&:hover': { bgcolor: '#dc2626' }
                    }}
                >
                    End Call Now
                </Button>
            </Paper>
        </Backdrop>
    );
};

export default ReconnectionOverlay;
