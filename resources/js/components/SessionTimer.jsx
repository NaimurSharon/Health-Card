import { useEffect, useState, useRef, useCallback } from 'react';
import { useCallStateHooks, useCall } from '@stream-io/video-react-sdk';
import { Box, Typography, Alert, Button } from '@mui/material';
import { AccessTime as ClockIcon, Warning as WarningIcon } from '@mui/icons-material';

/**
 * Session Timer Hook - Monitors call duration and enforces 15-minute limit
 * Based on: https://getstream.io/video/docs/react/ui-cookbook/session-timers/
 */
const useSessionTimer = (maxDurationMs = 15 * 60 * 1000) => {
    const { useCallStartedAt } = useCallStateHooks();
    const callStartedAt = useCallStartedAt();
    const [remainingMs, setRemainingMs] = useState(maxDurationMs);
    const [isExpired, setIsExpired] = useState(false);

    useEffect(() => {
        if (!callStartedAt) return;

        const startTime = new Date(callStartedAt).getTime();
        const endTime = startTime + maxDurationMs;

        const interval = setInterval(() => {
            const now = Date.now();
            const remaining = endTime - now;

            if (remaining <= 0) {
                setRemainingMs(0);
                setIsExpired(true);
                clearInterval(interval);
            } else {
                setRemainingMs(remaining);
            }
        }, 1000);

        return () => clearInterval(interval);
    }, [callStartedAt, maxDurationMs]);

    return { remainingMs, isExpired };
};

/**
 * Alert Hook - Triggers at specific time thresholds
 */
const useSessionTimerAlert = (remainingMs, threshold, onAlert) => {
    const didAlert = useRef(false);

    useEffect(() => {
        if (!didAlert.current && remainingMs <= threshold && remainingMs > 0) {
            didAlert.current = true;
            onAlert();
        }
    }, [remainingMs, threshold, onAlert]);
};

/**
 * Participant Disconnection Monitor
 * Ends call if a participant is disconnected for more than 2 minutes
 */
export const useParticipantDisconnectionMonitor = (onDisconnectionTimeout) => {
    const { useParticipants } = useCallStateHooks();
    const participants = useParticipants();
    const disconnectionTimers = useRef(new Map());

    useEffect(() => {
        participants.forEach((participant) => {
            const isConnected = participant.connectionQuality !== 'offline';
            const timerId = disconnectionTimers.current.get(participant.sessionId);

            if (!isConnected && !timerId) {
                // Start disconnection timer (2 minutes)
                const timer = setTimeout(() => {
                    console.log(`Participant ${participant.name} disconnected for 2 minutes`);
                    onDisconnectionTimeout(participant);
                }, 2 * 60 * 1000);

                disconnectionTimers.current.set(participant.sessionId, timer);
            } else if (isConnected && timerId) {
                // Clear timer if participant reconnects
                clearTimeout(timerId);
                disconnectionTimers.current.delete(participant.sessionId);
            }
        });

        // Cleanup on unmount
        return () => {
            disconnectionTimers.current.forEach((timer) => clearTimeout(timer));
            disconnectionTimers.current.clear();
        };
    }, [participants, onDisconnectionTimeout]);
};

/**
 * Format time in MM:SS format
 */
const formatTime = (ms) => {
    const totalSeconds = Math.floor(ms / 1000);
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
};

/**
 * Enhanced Session Timer Component with Alerts
 * - Shows remaining time
 * - Alerts at 5 minutes
 * - Alerts at 2 minutes
 * - Alerts at 1 minute
 * - Automatically ends call when time is up
 */
const SessionTimerWithAlerts = ({ onTimeExpired }) => {
    const { remainingMs, isExpired } = useSessionTimer();
    const [show5MinAlert, setShow5MinAlert] = useState(false);
    const [show2MinAlert, setShow2MinAlert] = useState(false);
    const [show1MinAlert, setShow1MinAlert] = useState(false);

    // 5 minute warning
    useSessionTimerAlert(remainingMs, 5 * 60 * 1000, useCallback(() => {
        setShow5MinAlert(true);
    }, []));

    // 2 minute warning
    useSessionTimerAlert(remainingMs, 2 * 60 * 1000, useCallback(() => {
        setShow2MinAlert(true);
        setShow5MinAlert(false);
    }, []));

    // 1 minute warning  
    useSessionTimerAlert(remainingMs, 1 * 60 * 1000, useCallback(() => {
        setShow1MinAlert(true);
        setShow2MinAlert(false);
    }, []));

    // Call expired
    useEffect(() => {
        if (isExpired && onTimeExpired) {
            onTimeExpired();
        }
    }, [isExpired, onTimeExpired]);

    const isWarning = remainingMs <= 2 * 60 * 1000;
    const isCritical = remainingMs <= 1 * 60 * 1000;

    return (
        <>
            {/* Timer Display - Top Center */}
            <Box
                sx={{
                    position: 'fixed',
                    top: { xs: 60, sm: 70 },
                    left: '50%',
                    transform: 'translateX(-50%)',
                    zIndex: 100,
                    bgcolor: isCritical
                        ? 'rgba(239, 68, 68, 0.9)'
                        : isWarning
                            ? 'rgba(255, 170, 0, 0.9)'
                            : 'rgba(0, 0, 0, 0.6)',
                    backdropFilter: 'blur(12px)',
                    border: `2px solid ${isCritical ? '#ef4444' : isWarning ? '#ffaa00' : 'rgba(255, 255, 255, 0.1)'}`,
                    borderRadius: 10,
                    px: 2,
                    py: 0.75,
                    display: 'flex',
                    alignItems: 'center',
                    gap: 1,
                    boxShadow: isCritical ? '0 8px 24px rgba(239, 68, 68, 0.5)' : '0 4px 12px rgba(0, 0, 0, 0.3)',
                    animation: isCritical ? 'pulse 1s infinite' : 'none',
                    '@keyframes pulse': {
                        '0%, 100%': { opacity: 1 },
                        '50%': { opacity: 0.7 }
                    }
                }}
            >
                <ClockIcon sx={{ fontSize: 16, color: 'white' }} />
                <Typography
                    variant="caption"
                    sx={{
                        color: 'white',
                        fontWeight: 700,
                        fontSize: { xs: '0.75rem', sm: '0.875rem' },
                        letterSpacing: '0.5px',
                        fontFamily: 'monospace'
                    }}
                >
                    {formatTime(remainingMs)}
                </Typography>
            </Box>

            {/* 5 Minute Alert */}
            {show5MinAlert && (
                <Alert
                    severity="warning"
                    icon={<WarningIcon />}
                    onClose={() => setShow5MinAlert(false)}
                    sx={{
                        position: 'fixed',
                        top: 120,
                        right: 20,
                        zIndex: 1000,
                        minWidth: 300,
                        boxShadow: '0 8px 24px rgba(0, 0, 0, 0.3)'
                    }}
                >
                    <Typography variant="body2" fontWeight={600}>
                        5 minutes remaining in this consultation
                    </Typography>
                </Alert>
            )}

            {/* 2 Minute Alert */}
            {show2MinAlert && (
                <Alert
                    severity="warning"
                    icon={<WarningIcon />}
                    onClose={() => setShow2MinAlert(false)}
                    sx={{
                        position: 'fixed',
                        top: 120,
                        right: 20,
                        zIndex: 1000,
                        minWidth: 300,
                        bgcolor: '#ffaa00',
                        color: 'white',
                        boxShadow: '0 8px 24px rgba(255, 170, 0, 0.5)',
                        '& .MuiAlert-icon': {
                            color: 'white'
                        }
                    }}
                >
                    <Typography variant="body2" fontWeight={700}>
                        2 minutes remaining - Please wrap up
                    </Typography>
                </Alert>
            )}

            {/* 1 Minute Critical Alert */}
            {show1MinAlert && (
                <Alert
                    severity="error"
                    icon={<WarningIcon />}
                    sx={{
                        position: 'fixed',
                        top: 120,
                        right: 20,
                        zIndex: 1000,
                        minWidth: 300,
                        bgcolor: '#ef4444',
                        color: 'white',
                        boxShadow: '0 8px 32px rgba(239, 68, 68, 0.6)',
                        '& .MuiAlert-icon': {
                            color: 'white'
                        },
                        animation: 'pulse 1s infinite'
                    }}
                >
                    <Typography variant="body2" fontWeight={700}>
                        ⚠️ FINAL MINUTE - Call will end soon!
                    </Typography>
                </Alert>
            )}
        </>
    );
};

export default SessionTimerWithAlerts;
