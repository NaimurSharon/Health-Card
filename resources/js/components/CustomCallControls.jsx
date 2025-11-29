import React from 'react';
import {
    useCallStateHooks,
    useCall
} from '@stream-io/video-react-sdk';
import {
    Box,
    IconButton,
    Tooltip,
    Fab
} from '@mui/material';
import {
    Mic as MicIcon,
    MicOff as MicOffIcon,
    Videocam as VideoIcon,
    VideocamOff as VideoOffIcon,
    CallEnd as CallEndIcon,
    Cameraswitch as SwitchCameraIcon
} from '@mui/icons-material';

const CustomCallControls = ({ onEndCall }) => {
    const call = useCall();
    const { useMicrophoneState, useCameraState } = useCallStateHooks();
    const { isMute: isMicMuted } = useMicrophoneState();
    const { isMute: isCamMuted } = useCameraState();

    const toggleMic = async () => {
        if (call) {
            await call.microphone.toggle();
        }
    };

    const toggleCam = async () => {
        if (call) {
            await call.camera.toggle();
        }
    };

    const toggleCameraFacing = async () => {
        if (call) {
            await call.camera.flip();
        }
    };

    return (
        <Box
            sx={{
                display: 'flex',
                alignItems: 'center',
                gap: 2,
                p: 1.5,
                borderRadius: 4,
                bgcolor: 'rgba(0, 0, 0, 0.6)',
                backdropFilter: 'blur(16px)',
                border: '1px solid rgba(255, 255, 255, 0.1)',
                boxShadow: '0 8px 32px rgba(0, 0, 0, 0.4)',
            }}
        >
            {/* Microphone Toggle */}
            <Tooltip title={isMicMuted ? "Unmute Microphone" : "Mute Microphone"}>
                <IconButton
                    onClick={toggleMic}
                    sx={{
                        bgcolor: isMicMuted ? 'rgba(239, 68, 68, 0.2)' : 'rgba(255, 255, 255, 0.1)',
                        color: isMicMuted ? '#ef4444' : 'white',
                        border: `1px solid ${isMicMuted ? 'rgba(239, 68, 68, 0.5)' : 'rgba(255, 255, 255, 0.1)'}`,
                        width: 48,
                        height: 48,
                        '&:hover': {
                            bgcolor: isMicMuted ? 'rgba(239, 68, 68, 0.3)' : 'rgba(255, 255, 255, 0.2)',
                        }
                    }}
                >
                    {isMicMuted ? <MicOffIcon /> : <MicIcon />}
                </IconButton>
            </Tooltip>

            {/* Camera Toggle */}
            <Tooltip title={isCamMuted ? "Turn On Camera" : "Turn Off Camera"}>
                <IconButton
                    onClick={toggleCam}
                    sx={{
                        bgcolor: isCamMuted ? 'rgba(239, 68, 68, 0.2)' : 'rgba(255, 255, 255, 0.1)',
                        color: isCamMuted ? '#ef4444' : 'white',
                        border: `1px solid ${isCamMuted ? 'rgba(239, 68, 68, 0.5)' : 'rgba(255, 255, 255, 0.1)'}`,
                        width: 48,
                        height: 48,
                        '&:hover': {
                            bgcolor: isCamMuted ? 'rgba(239, 68, 68, 0.3)' : 'rgba(255, 255, 255, 0.2)',
                        }
                    }}
                >
                    {isCamMuted ? <VideoOffIcon /> : <VideoIcon />}
                </IconButton>
            </Tooltip>

            {/* Switch Camera (Mobile only usually, but good to have) */}
            <Tooltip title="Switch Camera">
                <IconButton
                    onClick={toggleCameraFacing}
                    sx={{
                        bgcolor: 'rgba(255, 255, 255, 0.1)',
                        color: 'white',
                        border: '1px solid rgba(255, 255, 255, 0.1)',
                        width: 48,
                        height: 48,
                        display: { xs: 'flex', md: 'none' }, // Hide on desktop
                        '&:hover': {
                            bgcolor: 'rgba(255, 255, 255, 0.2)',
                        }
                    }}
                >
                    <SwitchCameraIcon />
                </IconButton>
            </Tooltip>

            {/* End Call Button */}
            <Tooltip title="End Call">
                <Fab
                    color="error"
                    onClick={onEndCall}
                    sx={{
                        boxShadow: '0 0 20px rgba(239, 68, 68, 0.5)',
                        width: 56,
                        height: 56,
                    }}
                >
                    <CallEndIcon />
                </Fab>
            </Tooltip>
        </Box>
    );
};

export default CustomCallControls;