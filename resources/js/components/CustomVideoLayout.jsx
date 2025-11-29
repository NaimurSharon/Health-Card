import React from 'react';
import {
    useCallStateHooks,
    ParticipantView,
    useCall,
    hasScreenShare
} from '@stream-io/video-react-sdk';
import { Box, Typography } from '@mui/material';

/**
 * WhatsApp/Messenger-style Video Layout for 2 participants
 * - Remote participant: Fullscreen
 * - Local participant: Small floating corner (PiP)
 * 
 * Following Stream.io best practices from:
 * https://getstream.io/video/docs/react/ui-cookbook/video-layout/
 * https://getstream.io/video/docs/react/ui-cookbook/participant-view-customizations/
 */
const CustomVideoLayout = () => {
    const call = useCall();
    const { useParticipants, useLocalParticipant } = useCallStateHooks();
    const participants = useParticipants();
    const localParticipant = useLocalParticipant();

    // For a 2-person call: separate local and remote participants
    const remoteParticipant = participants.find(
        (p) => p.sessionId !== localParticipant?.sessionId
    );

    // Determine which participant to show in fullscreen
    // Priority: remote participant > local participant (if alone)
    const mainParticipant = remoteParticipant || localParticipant;
    const isLocalMain = mainParticipant === localParticipant;

    // Show loading state if no participants yet
    if (!call || !mainParticipant) {
        return (
            <Box
                sx={{
                    position: 'absolute',
                    inset: 0,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    bgcolor: '#000',
                    color: 'white'
                }}
            >
                <Typography variant="h6">Connecting...</Typography>
            </Box>
        );
    }

    return (
        <Box
            className="custom-video-layout"
            sx={{
                position: 'absolute',
                inset: 0,
                width: '100%',
                height: '100%',
                bgcolor: '#000',
                overflow: 'hidden'
            }}
        >
            {/* Main Fullscreen Video - Remote Participant (or local if alone) */}
            <Box
                className="main-participant-container"
                sx={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    right: 0,
                    bottom: 0,
                    width: '100%',
                    height: '100%',
                    '& .str-video__participant-view': {
                        width: '100%',
                        height: '100%',
                        borderRadius: 0,
                    },
                    '& .str-video__video': {
                        objectFit: 'cover',
                        width: '100%',
                        height: '100%',
                    }
                }}
            >
                <ParticipantView
                    participant={mainParticipant}
                    trackType={
                        hasScreenShare(mainParticipant)
                            ? 'screenShareTrack'
                            : 'videoTrack'
                    }
                    ParticipantViewUI={
                        <Box
                            sx={{
                                position: 'absolute',
                                bottom: { xs: 90, sm: 110 },
                                left: 16,
                                bgcolor: 'rgba(0, 0, 0, 0.6)',
                                backdropFilter: 'blur(10px)',
                                px: 2,
                                py: 1,
                                borderRadius: 2,
                                border: '1px solid rgba(255, 255, 255, 0.1)',
                                boxShadow: '0 4px 12px rgba(0, 0, 0, 0.3)'
                            }}
                        >
                            <Typography
                                variant="subtitle2"
                                sx={{
                                    color: 'white',
                                    fontWeight: 600,
                                    fontSize: { xs: '0.75rem', sm: '0.875rem' }
                                }}
                            >
                                {mainParticipant.name || mainParticipant.userId}
                                {isLocalMain && ' (You)'}
                            </Typography>
                        </Box>
                    }
                />
            </Box>

            {/* Floating Local Video (PiP) - Only show if we have a remote participant */}
            {remoteParticipant && localParticipant && (
                <Box
                    className="local-participant-pip"
                    sx={{
                        position: 'absolute',
                        top: { xs: 70, sm: 76 },
                        right: { xs: 12, sm: 16 },
                        width: { xs: 90, sm: 140, md: 180 },
                        height: { xs: 120, sm: 187, md: 240 },
                        zIndex: 100,
                        borderRadius: 3,
                        overflow: 'hidden',
                        boxShadow: '0 8px 24px rgba(0, 0, 0, 0.6)',
                        border: '2px solid rgba(255, 255, 255, 0.15)',
                        bgcolor: '#1a1a1a',
                        transition: 'transform 0.2s ease-in-out',
                        '&:hover': {
                            transform: 'scale(1.05)',
                            boxShadow: '0 12px 32px rgba(0, 0, 0, 0.8)',
                        },
                        '& .str-video__participant-view': {
                            width: '100%',
                            height: '100%',
                            borderRadius: 0,
                        },
                        '& .str-video__video': {
                            objectFit: 'cover',
                            width: '100%',
                            height: '100%',
                        }
                    }}
                >
                    <ParticipantView
                        participant={localParticipant}
                        trackType="videoTrack"
                        ParticipantViewUI={
                            <Box
                                sx={{
                                    position: 'absolute',
                                    bottom: 6,
                                    left: 6,
                                    right: 6,
                                    bgcolor: 'rgba(0, 0, 0, 0.7)',
                                    backdropFilter: 'blur(8px)',
                                    px: 1,
                                    py: 0.5,
                                    borderRadius: 1,
                                    textAlign: 'center'
                                }}
                            >
                                <Typography
                                    variant="caption"
                                    sx={{
                                        color: 'white',
                                        fontWeight: 600,
                                        fontSize: { xs: '0.625rem', sm: '0.7rem' }
                                    }}
                                >
                                    You
                                </Typography>
                            </Box>
                        }
                    />
                </Box>
            )}
        </Box>
    );
};

export default CustomVideoLayout;
