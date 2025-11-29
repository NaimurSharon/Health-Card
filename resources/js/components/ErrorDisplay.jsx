import React from 'react';
import { Box, Typography, Button, Paper, Avatar } from '@mui/material';
import { ErrorOutline as ErrorIcon, Refresh as RetryIcon } from '@mui/icons-material';

const ErrorDisplay = ({ message, onRetry }) => {
    return (
        <Box
            sx={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                height: '100vh',
                bgcolor: 'background.default',
                p: 2
            }}
        >
            <Paper
                elevation={10}
                sx={{
                    p: 4,
                    textAlign: 'center',
                    maxWidth: 400,
                    borderRadius: 4,
                    bgcolor: 'background.paper',
                    border: '1px solid rgba(239, 68, 68, 0.2)'
                }}
            >
                <Box sx={{ display: 'flex', justifyContent: 'center', mb: 2 }}>
                    <Avatar sx={{ bgcolor: 'error.main', width: 56, height: 56 }}>
                        <ErrorIcon fontSize="large" />
                    </Avatar>
                </Box>

                <Typography variant="h5" gutterBottom sx={{ fontWeight: 700 }}>
                    Error
                </Typography>

                <Typography variant="body1" color="text.secondary" sx={{ mb: 4 }}>
                    {message}
                </Typography>

                {onRetry && (
                    <Button
                        variant="contained"
                        color="primary"
                        startIcon={<RetryIcon />}
                        onClick={onRetry}
                        fullWidth
                    >
                        Retry
                    </Button>
                )}
            </Paper>
        </Box>
    );
};

export default ErrorDisplay;
