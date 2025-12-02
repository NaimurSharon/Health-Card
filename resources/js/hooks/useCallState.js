import { useState, useEffect, useRef } from 'react';

/**
 * Call States:
 * - 'waiting': In waiting room, checking for other participant
 * - 'ready': Both participants present, about to start call
 * - 'connecting': Creating Stream call
 * - 'active': Call in progress
 * - 'reconnecting': Participant disconnected, waiting for rejoin
 * - 'ending': Call being terminated
 * - 'ended': Call completed
 */

export const useCallState = (consultation, userType) => {
    const [callState, setCallState] = useState('waiting');
    const [otherParticipantPresent, setOtherParticipantPresent] = useState(false);
    const [reconnectionTimer, setReconnectionTimer] = useState(null);
    const [disconnectedParticipant, setDisconnectedParticipant] = useState(null);

    const pollingIntervalRef = useRef(null);
    const reconnectionTimeoutRef = useRef(null);

    // Poll for other participant presence
    const checkParticipantPresence = async () => {
        try {
            // Use correct endpoint based on user type
            // Student routes have no prefix, doctor routes have /doctor prefix
            const endpoint = userType === 'student'
                ? `/student/video-consultations/${consultation.id}/presence`
                : `/doctor/video-consultations/${consultation.id}/presence`;

            console.log(`[WaitingRoom] Checking presence for ${userType} at ${endpoint}`);

            const response = await fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                console.log(`[WaitingRoom] Presence response:`, data);

                const otherPresent = userType === 'student'
                    ? data.doctor_present
                    : data.student_present;

                console.log(`[WaitingRoom] Other participant (${userType === 'student' ? 'doctor' : 'student'}) present:`, otherPresent);
                setOtherParticipantPresent(otherPresent);

                // If both present and in waiting state, transition to ready
                if (otherPresent && callState === 'waiting') {
                    console.log('[WaitingRoom] Both participants ready! Transitioning to ready state');
                    setCallState('ready');
                }
            }
        } catch (error) {
            console.error('Failed to check participant presence:', error);
        }
    };

    // Mark self as present in waiting room
    const markPresent = async () => {
        try {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // Use correct endpoint based on user type
            // Student routes have no prefix, doctor routes have /doctor prefix
            const endpoint = userType === 'student'
                ? `/student/video-consultations/${consultation.id}/ready`
                : `/doctor/video-consultations/${consultation.id}/ready`;

            console.log(`[WaitingRoom] Marking ${userType} as present at ${endpoint}`);

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ userType })
            });

            if (response.ok) {
                const data = await response.json();
                console.log(`[WaitingRoom] Mark present response:`, data);
            }
        } catch (error) {
            console.error('Failed to mark presence:', error);
        }
    };

    // Start waiting room polling
    const enterWaitingRoom = () => {
        console.log(`[WaitingRoom] Entering waiting room for ${userType}`);
        setCallState('waiting');
        markPresent();

        // Poll every 2 seconds for presence check
        pollingIntervalRef.current = setInterval(() => {
            checkParticipantPresence();
            markPresent(); // Keep refreshing our presence timestamp
        }, 2000);
        checkParticipantPresence(); // Immediate check
    };

    // Stop waiting room polling
    const exitWaitingRoom = () => {
        if (pollingIntervalRef.current) {
            clearInterval(pollingIntervalRef.current);
            pollingIntervalRef.current = null;
        }
    };

    // Handle participant disconnect
    const handleParticipantDisconnect = (participantInfo) => {
        setDisconnectedParticipant(participantInfo);
        setCallState('reconnecting');

        let timeLeft = 60;
        setReconnectionTimer(timeLeft);

        // Countdown timer
        const countdownInterval = setInterval(() => {
            timeLeft -= 1;
            setReconnectionTimer(timeLeft);

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);

        // 60-second timeout
        reconnectionTimeoutRef.current = setTimeout(() => {
            clearInterval(countdownInterval);
            setCallState('ending');
            // Trigger call end
        }, 60000);
    };

    // Handle participant reconnect
    const handleParticipantReconnect = () => {
        if (reconnectionTimeoutRef.current) {
            clearTimeout(reconnectionTimeoutRef.current);
            reconnectionTimeoutRef.current = null;
        }
        setDisconnectedParticipant(null);
        setReconnectionTimer(null);
        setCallState('active');
    };

    // Cleanup on unmount
    useEffect(() => {
        return () => {
            exitWaitingRoom();
            if (reconnectionTimeoutRef.current) {
                clearTimeout(reconnectionTimeoutRef.current);
            }
        };
    }, []);

    return {
        callState,
        setCallState,
        otherParticipantPresent,
        reconnectionTimer,
        disconnectedParticipant,
        enterWaitingRoom,
        exitWaitingRoom,
        handleParticipantDisconnect,
        handleParticipantReconnect
    };
};
