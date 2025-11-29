import React, { useState, useEffect } from 'react';
import { ThemeProvider } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';
import theme from '../theme';
import VideoCall from './VideoCall';
import LoadingSpinner from './LoadingSpinner';
import ErrorDisplay from './ErrorDisplay';

const App = ({ initialConfig = null }) => {
    const [config, setConfig] = useState(initialConfig);
    const [loading, setLoading] = useState(!initialConfig);
    const [error, setError] = useState(null);

    useEffect(() => {
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
    );
};

export default App;