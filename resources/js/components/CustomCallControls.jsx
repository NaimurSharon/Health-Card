<<<<<<< HEAD
import React, { useState, useEffect } from 'react';
import { useCall, useCallStateHooks } from '@stream-io/video-react-sdk';
import { Mic, MicOff, Video, VideoOff, PhoneOff } from 'lucide-react';
=======
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
>>>>>>> c356163 (video call ui setup)

const CustomCallControls = ({ onEndCall }) => {
    const call = useCall();
    const { useMicrophoneState, useCameraState } = useCallStateHooks();
<<<<<<< HEAD
    const { microphone, isMicrophoneEnabled } = useMicrophoneState();
    const { camera, isCameraEnabled } = useCameraState();
    const [isMobile, setIsMobile] = useState(false);

    useEffect(() => {
        const checkMobile = () => {
            setIsMobile(window.innerWidth < 768);
        };

        checkMobile();
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    const handleToggleMic = async () => {
        try {
            if (isMicrophoneEnabled) {
                await microphone.disable();
            } else {
                await microphone.enable();
            }
        } catch (err) {
            console.error('Mic toggle error:', err);
        }
    };

    const handleToggleCamera = async () => {
        try {
            if (isCameraEnabled) {
                await camera.disable();
            } else {
                await camera.enable();
            }
        } catch (err) {
            console.error('Camera toggle error:', err);
        }
    };

    const handleLeaveCall = async () => {
        try {
            console.log('Leave button clicked, calling onEndCall handler');
            if (onEndCall && typeof onEndCall === 'function') {
                await onEndCall();
            } else {
                console.warn('onEndCall handler not provided or not a function');
            }
        } catch (err) {
            console.error('Error leaving call:', err);
        }
    };

    if (!call) {
        return null;
    }

    if (isMobile) {
        return (
            <div className="flex items-center justify-center gap-3 bg-gradient-to-r from-slate-900/80 via-slate-800/80 to-slate-900/80 backdrop-blur-md rounded-full px-4 py-3 shadow-2xl border border-slate-700/50">
                {/* Microphone Toggle */}
                <button
                    onClick={handleToggleMic}
                    className={`p-3 rounded-full transition-all duration-200 transform active:scale-95 ${
                        isMicrophoneEnabled
                            ? 'bg-gradient-to-br from-slate-700 to-slate-800 hover:from-slate-600 hover:to-slate-700 text-white shadow-lg'
                            : 'bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white shadow-lg shadow-red-500/50'
                    }`}
                    title={isMicrophoneEnabled ? 'Mute microphone' : 'Unmute microphone'}
                >
                    {isMicrophoneEnabled ? (
                        <Mic size={20} />
                    ) : (
                        <MicOff size={20} />
                    )}
                </button>

                {/* Camera Toggle */}
                <button
                    onClick={handleToggleCamera}
                    className={`p-3 rounded-full transition-all duration-200 transform active:scale-95 ${
                        isCameraEnabled
                            ? 'bg-gradient-to-br from-slate-700 to-slate-800 hover:from-slate-600 hover:to-slate-700 text-white shadow-lg'
                            : 'bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white shadow-lg shadow-red-500/50'
                    }`}
                    title={isCameraEnabled ? 'Turn off camera' : 'Turn on camera'}
                >
                    {isCameraEnabled ? (
                        <Video size={20} />
                    ) : (
                        <VideoOff size={20} />
                    )}
                </button>

                {/* End Call Button */}
                <button
                    onClick={handleLeaveCall}
                    className="p-3 rounded-full bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white transition-all duration-200 transform active:scale-95 shadow-lg shadow-red-500/50"
                    title="End call"
                >
                    <PhoneOff size={20} />
                </button>
            </div>
        );
    }

    // Desktop View
    return (
        <div className="flex items-center justify-center gap-4 bg-gradient-to-r from-slate-900/80 via-slate-800/80 to-slate-900/80 backdrop-blur-md rounded-full px-8 py-4 shadow-2xl border border-slate-700/50">
            {/* Microphone Toggle */}
            <button
                onClick={handleToggleMic}
                className={`p-4 rounded-full transition-all duration-200 transform hover:scale-110 active:scale-95 ${
                    isMicrophoneEnabled
                        ? 'bg-gradient-to-br from-slate-700 to-slate-800 hover:from-slate-600 hover:to-slate-700 text-white shadow-lg'
                        : 'bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white shadow-lg shadow-red-500/50'
                }`}
                title={isMicrophoneEnabled ? 'Mute microphone' : 'Unmute microphone'}
            >
                {isMicrophoneEnabled ? (
                    <Mic size={22} />
                ) : (
                    <MicOff size={22} />
                )}
            </button>

            {/* Camera Toggle */}
            <button
                onClick={handleToggleCamera}
                className={`p-4 rounded-full transition-all duration-200 transform hover:scale-110 active:scale-95 ${
                    isCameraEnabled
                        ? 'bg-gradient-to-br from-slate-700 to-slate-800 hover:from-slate-600 hover:to-slate-700 text-white shadow-lg'
                        : 'bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white shadow-lg shadow-red-500/50'
                }`}
                title={isCameraEnabled ? 'Turn off camera' : 'Turn on camera'}
            >
                {isCameraEnabled ? (
                    <Video size={22} />
                ) : (
                    <VideoOff size={22} />
                )}
            </button>

            {/* Divider */}
            <div className="w-px h-8 bg-slate-600/50"></div>

            {/* End Call Button */}
            <button
                onClick={handleLeaveCall}
                className="p-4 rounded-full bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white transition-all duration-200 transform hover:scale-110 active:scale-95 shadow-lg shadow-red-500/50"
                title="End call"
            >
                <PhoneOff size={22} />
            </button>
        </div>
    );
};

export default CustomCallControls;
=======
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
>>>>>>> c356163 (video call ui setup)
