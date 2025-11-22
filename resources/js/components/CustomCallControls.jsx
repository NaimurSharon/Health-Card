import React, { useState, useEffect } from 'react';
import { useCall, useCallStateHooks } from '@stream-io/video-react-sdk';
import { Mic, MicOff, Video, VideoOff, PhoneOff } from 'lucide-react';

const CustomCallControls = ({ onEndCall }) => {
    const call = useCall();
    const { useMicrophoneState, useCameraState } = useCallStateHooks();
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
