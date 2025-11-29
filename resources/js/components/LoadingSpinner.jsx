import React from 'react';
<<<<<<< HEAD

const LoadingSpinner = ({ message = "Loading..." }) => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-900">
            <div className="text-center">
                <div className="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p className="text-white text-lg font-semibold">{message}</p>
            </div>
        </div>
=======
import { Box, CircularProgress, Typography } from '@mui/material';

const LoadingSpinner = ({ message = "Loading..." }) => {
    return (
        <Box
            sx={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                justifyContent: 'center',
                height: '100vh',
                bgcolor: 'background.default'
            }}
        >
            <CircularProgress size={60} thickness={4} sx={{ mb: 3 }} />
            <Typography variant="h6" sx={{ color: 'text.primary', fontWeight: 600 }}>
                {message}
            </Typography>
        </Box>
>>>>>>> c356163 (video call ui setup)
    );
};

export default LoadingSpinner;
