<<<<<<< HEAD
import React, { useEffect, useState } from 'react';
=======
import React, { useState, useEffect } from 'react';
import { ThemeProvider } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';
import theme from '../theme';
>>>>>>> c356163 (video call ui setup)
import VideoCall from './VideoCall';
import LoadingSpinner from './LoadingSpinner';
import ErrorDisplay from './ErrorDisplay';

const App = ({ initialConfig = null }) => {
    const [config, setConfig] = useState(initialConfig);
    const [loading, setLoading] = useState(!initialConfig);
    const [error, setError] = useState(null);

    useEffect(() => {
<<<<<<< HEAD
        // If initial config is provided, skip API call
        if (initialConfig) {
            console.log('App: Using provided initial config');
            setConfig(initialConfig);
            setLoading(false);
            return;
        }

        // Otherwise, try API call
        const initializeApp = async () => {
            try {
                // Get consultation ID from server-passed global or from URL (fallback)
                const consultationId = window.consultationId || (() => {
                    const urlParts = window.location.pathname.split('/').filter(Boolean);
                    // If route is /consultations/{id}/video-call the id is the second last segment
                    if (urlParts.length >= 2 && urlParts[urlParts.length - 1] === 'video-call') {
                        return urlParts[urlParts.length - 2];
                    }
                    return urlParts[urlParts.length - 1];
                })();
                
                console.log('App: Fetching config from API for consultation:', consultationId);
                const response = await fetch(`/api/video-call/config/${consultationId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    
                    // Handle call already ended (403 error)
                    if (response.status === 403 && errorData.status === 'completed') {
                        setError('This call has already ended. You cannot rejoin.');
                        setLoading(false);
                        return;
                    }
                    
                    throw new Error(`Failed to load call configuration: ${response.status}`);
                }

                const data = await response.json();
                setConfig(data);
                setLoading(false);
            } catch (err) {
                console.error('Error initializing app:', err);
                setError(err.message);
                setLoading(false);
            }
        };

        initializeApp();
    }, [initialConfig]);

    if (loading) {
        return <LoadingSpinner message="Loading video call..." />;
    }

    if (error) {
        return <ErrorDisplay message={error} onRetry={() => window.location.reload()} />;
    }

    if (!config) {
        return <ErrorDisplay message="Configuration not found" />;
    }

    return (
        <VideoCall 
            streamConfig={config.streamConfig}
            consultation={config.consultation}
            userType={config.userType}
        />
=======
        if (!initialConfig) {
            const fetchConfig = async () => {
                try {
                    // Extract consultation ID from URL path
                    const pathParts = window.location.pathname.split('/');
                    // Handle both /doctor/consultations/{id}/video-call and /student/video-consultations/{id}
                    // and /video-consultations/{id}/join
                    const consultationId = window.consultationId || (() => {
                        const urlParts = window.location.pathname.split('/');
                        if (urlParts.includes('video-call')) {
                            return urlParts[urlParts.indexOf('video-call') - 1]; // for doctor
                        }
                        return urlParts[urlParts.length - 1];
                    })();

                    console.log('App: Fetching config from API for consultation:', consultationId);
                    const response = await fetch(`/api/video-call/config/${consultationId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));

                        // Handle call already ended (403 error)
                        if (response.status === 403 && errorData.status === 'completed') {
                            setError('This call has already ended. You cannot rejoin.');
                            setLoading(false);
                            return;
                        }

                        throw new Error(`Failed to load call configuration: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('App: Config loaded:', data);
                    setConfig(data);
                } catch (err) {
                    console.error('App: Error loading config:', err);
                    setError(err.message);
                } finally {
                    setLoading(false);
                }
            };

            fetchConfig();
        }
    }, [initialConfig]);

    if (loading) {
        return (
            <ThemeProvider theme={theme}>
                <CssBaseline />
                <LoadingSpinner message="Loading call configuration..." />
            </ThemeProvider>
        );
    }

    if (error) {
        return (
            <ThemeProvider theme={theme}>
                <CssBaseline />
                <ErrorDisplay message={error} />
            </ThemeProvider>
        );
    }

    if (!config) {
        return (
            <ThemeProvider theme={theme}>
                <CssBaseline />
                <ErrorDisplay message="Configuration not found" />
            </ThemeProvider>
        );
    }

    return (
        <ThemeProvider theme={theme}>
            <CssBaseline />
            <VideoCall
                streamConfig={config.streamConfig}
                consultation={config.consultation}
                userType={config.userType}
            />
        </ThemeProvider>
>>>>>>> c356163 (video call ui setup)
    );
};

export default App;