import React from 'react';
<<<<<<< HEAD

const ErrorDisplay = ({ message, onRetry }) => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-900">
            <div className="text-center max-w-sm">
                <div className="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i className="fas fa-exclamation-triangle text-xl text-white"></i>
                </div>
                <h3 className="text-lg font-semibold text-white mb-2">Error</h3>
                <p className="text-gray-300 mb-4">{message}</p>
                {onRetry && (
                    <button
                        onClick={onRetry}
                        className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <i className="fas fa-redo mr-2"></i>Retry
                    </button>
                )}
            </div>
        </div>
=======
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
>>>>>>> c356163 (video call ui setup)
    );
};

export default ErrorDisplay;
