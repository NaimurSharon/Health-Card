<<<<<<< HEAD
import React, { useEffect, useState } from 'react';
import { Phone, PhoneOff, Clock } from 'lucide-react';

const WaitingScreen = ({ consultation, userType, onCancel, participantReturnCountdown }) => {
    const [isMobile, setIsMobile] = useState(false);

    useEffect(() => {
        const checkMobile = () => {
            setIsMobile(window.innerWidth < 768);
        };

        checkMobile();
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    const formatCountdown = (seconds) => {
        if (!seconds) return '60s';
        return `${seconds}s`;
    };

    if (isMobile) {
        return (
            <div className="h-screen w-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-900 via-slate-950 to-black p-4">
                {/* Avatar - Larger on mobile */}
                <div className="mb-8 relative">
                    <div className="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-2xl animate-pulse">
                        <span className="text-5xl text-white font-bold">
                            {userType === 'student' 
                                ? (consultation?.doctor?.name?.[0] || 'D').toUpperCase()
                                : (consultation?.student?.user?.name?.[0] || 'S').toUpperCase()
                            }
                        </span>
                    </div>
                    <div className="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                </div>

                {/* Info */}
                <div className="text-center mb-12">
                    <h1 className="text-3xl font-bold text-white mb-2">
                        {userType === 'student' 
                            ? `Dr. ${consultation?.doctor?.name || 'Doctor'}`
                            : consultation?.student?.user?.name || 'Student'
                        }
                    </h1>
                    <p className="text-gray-400 text-sm">Waiting for participant to join...</p>
                </div>

                {/* Status with animation */}
                <div className="mb-12 flex flex-col items-center gap-4">
                    <div className="flex items-center justify-center">
                        <div className="w-4 h-4 bg-green-500 rounded-full animate-pulse mr-3"></div>
                        <span className="text-white text-sm">Call ready</span>
                    </div>
                    <div className="flex items-center gap-2 bg-blue-500/20 backdrop-blur text-blue-300 px-4 py-2 rounded-lg border border-blue-500/30">
                        <Clock size={16} />
                        <span className="text-sm">Timeout in {formatCountdown(participantReturnCountdown)}</span>
                    </div>
                </div>

                {/* Floating particles effect */}
                <div className="absolute inset-0 overflow-hidden pointer-events-none">
                    {[...Array(3)].map((_, i) => (
                        <div
                            key={i}
                            className="absolute w-2 h-2 bg-blue-500 rounded-full opacity-50"
                            style={{
                                left: `${Math.random() * 100}%`,
                                top: `${Math.random() * 100}%`,
                                animation: `float ${3 + i}s infinite ease-in-out`,
                            }}
                        />
                    ))}
                </div>

                {/* Cancel Button */}
                <div className="mt-auto w-full">
                    <button
                        onClick={onCancel}
                        className="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white py-4 rounded-full font-semibold transition-all duration-200 transform active:scale-95 shadow-lg"
                    >
                        <PhoneOff size={24} />
                        <span>Cancel Call</span>
                    </button>
                </div>

                <style>{`
                    @keyframes float {
                        0%, 100% {
                            transform: translateY(0px) translateX(0px);
                            opacity: 0.3;
                        }
                        50% {
                            transform: translateY(-30px) translateX(10px);
                            opacity: 0.7;
                        }
                    }
                `}</style>
            </div>
        );
    }

    // Desktop View
    return (
        <div className="h-screen w-full flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-950 to-black p-8">
            <div className="flex flex-col items-center space-y-8 max-w-md w-full">
                {/* Large Avatar */}
                <div className="relative">
                    <div className="w-40 h-40 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-2xl animate-pulse">
                        <span className="text-7xl text-white font-bold">
                            {userType === 'student' 
                                ? (consultation?.doctor?.name?.[0] || 'D').toUpperCase()
                                : (consultation?.student?.user?.name?.[0] || 'S').toUpperCase()
                            }
                        </span>
                    </div>
                    <div className="absolute bottom-2 right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white"></div>
                </div>

                {/* Text Info */}
                <div className="text-center space-y-2">
                    <h1 className="text-4xl font-bold text-white">
                        {userType === 'student' 
                            ? `Dr. ${consultation?.doctor?.name || 'Doctor'}`
                            : consultation?.student?.user?.name || 'Student'
                        }
                    </h1>
                    <p className="text-gray-400">Waiting for participant to join...</p>
                </div>

                {/* Status */}
                <div className="space-y-3 w-full">
                    <div className="flex items-center justify-center gap-3 text-white">
                        <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span className="text-sm">Call is ready</span>
                    </div>
                    <div className="bg-blue-500/20 backdrop-blur text-blue-300 px-6 py-3 rounded-lg border border-blue-500/30 flex items-center justify-center gap-2 text-sm">
                        <Clock size={16} />
                        <span>Timeout in {formatCountdown(participantReturnCountdown)}</span>
                    </div>
                </div>

                {/* Animated Background Shapes */}
                <div className="absolute inset-0 overflow-hidden pointer-events-none opacity-20">
                    <div className="absolute top-1/4 left-1/4 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
                    <div className="absolute top-1/3 right-1/4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
                </div>

                {/* Cancel Button */}
                <button
                    onClick={onCancel}
                    className="mt-8 w-full flex items-center justify-center gap-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white py-4 px-6 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-red-500/50"
                >
                    <PhoneOff size={24} />
                    <span>Cancel Call</span>
                </button>

                <style>{`
                    @keyframes blob {
                        0%, 100% {
                            transform: translate(0, 0) scale(1);
                        }
                        33% {
                            transform: translate(30px, -50px) scale(1.1);
                        }
                        66% {
                            transform: translate(-20px, 20px) scale(0.9);
                        }
                    }
                    .animate-blob {
                        animation: blob 7s infinite;
                    }
                    .animation-delay-2000 {
                        animation-delay: 2s;
                    }
                `}</style>
=======
import React from 'react';
import { Video, X } from 'lucide-react';

const WaitingScreen = ({ onCancel, userType }) => {
    return (
        <div className="waiting-screen">
            <style jsx>{`
                .waiting-screen {
                    height: 100vh;
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
                    position: relative;
                    overflow: hidden;
                }

                .background-animation {
                    position: absolute;
                    border-radius: 50%;
                    filter: blur(100px);
                    animation: pulse 3s ease-in-out infinite;
                }

                .animation-1 {
                    top: 25%;
                    left: 25%;
                    width: 24rem;
                    height: 24rem;
                    background: rgba(59, 130, 246, 0.2);
                    animation-delay: 0s;
                }

                .animation-2 {
                    bottom: 25%;
                    right: 25%;
                    width: 24rem;
                    height: 24rem;
                    background: rgba(139, 92, 246, 0.2);
                    animation-delay: 1.5s;
                }

                .glass-card {
                    position: relative;
                    z-index: 10;
                    background: rgba(255, 255, 255, 0.05);
                    backdrop-filter: blur(40px);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    padding: 2rem;
                    border-radius: 1.5rem;
                    box-shadow: 
                        0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.05);
                    max-width: 28rem;
                    width: 100%;
                    margin: 0 1rem;
                    text-align: center;
                    animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                }

                @media (min-width: 640px) {
                    .glass-card {
                        padding: 3rem;
                        border-radius: 2rem;
                    }
                }

                .avatar-container {
                    position: relative;
                    display: inline-block;
                    margin-bottom: 2rem;
                }

                .avatar {
                    width: 6rem;
                    height: 6rem;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 
                        0 8px 32px rgba(59, 130, 246, 0.4),
                        inset 0 4px 8px rgba(255, 255, 255, 0.2);
                    border: 2px solid rgba(255, 255, 255, 0.2);
                    position: relative;
                    z-index: 10;
                    animation: bounce 2s ease-in-out infinite;
                }

                @media (min-width: 640px) {
                    .avatar {
                        width: 8rem;
                        height: 8rem;
                    }
                }

                .ripple-1, .ripple-2 {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    background: rgba(59, 130, 246, 0.3);
                    animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
                }

                .ripple-2 {
                    animation-delay: 1s;
                }

                .title {
                    font-size: 1.5rem;
                    font-weight: bold;
                    color: white;
                    margin-bottom: 0.75rem;
                }

                @media (min-width: 640px) {
                    .title {
                        font-size: 1.875rem;
                    margin-bottom: 1rem;
                    }
                }

                .description {
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 1rem;
                    margin-bottom: 2rem;
                }

                @media (min-width: 640px) {
                    .description {
                        font-size: 1.125rem;
                        margin-bottom: 2.5rem;
                    }
                }

                .cancel-button {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.75rem;
                    width: 100%;
                    padding: 1rem;
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 1rem;
                    color: white;
                    font-weight: 500;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    cursor: pointer;
                }

                .cancel-button:hover {
                    background: rgba(239, 68, 68, 0.2);
                    border-color: rgba(239, 68, 68, 0.5);
                    transform: translateY(-2px);
                    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
                }

                .cancel-button:active {
                    transform: translateY(0);
                }

                .cancel-icon {
                    width: 2rem;
                    height: 2rem;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.1);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background-color 0.3s ease;
                }

                .cancel-button:hover .cancel-icon {
                    background: rgba(239, 68, 68, 0.3);
                }

                /* Animations */
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @keyframes bounce {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-10px);
                    }
                }

                @keyframes ping {
                    75%, 100% {
                        transform: scale(2);
                        opacity: 0;
                    }
                }

                @keyframes pulse {
                    0%, 100% {
                        opacity: 0.5;
                        transform: scale(1);
                    }
                    50% {
                        opacity: 0.8;
                        transform: scale(1.1);
                    }
                }

                /* Mobile optimizations */
                @media (max-width: 640px) {
                    .waiting-screen {
                        height: 100dvh;
                    }

                    .animation-1, .animation-2 {
                        width: 16rem;
                        height: 16rem;
                    }

                    .avatar {
                        width: 5rem;
                        height: 5rem;
                    }

                    .title {
                        font-size: 1.25rem;
                    }

                    .description {
                        font-size: 0.9rem;
                    }

                    .cancel-button {
                        padding: 0.875rem;
                        border-radius: 0.875rem;
                    }
                }
            `}</style>

            {/* Background Animations */}
            <div className="background-animation animation-1"></div>
            <div className="background-animation animation-2"></div>

            {/* Glass Card */}
            <div className="glass-card">
                <div className="avatar-container">
                    <div className="avatar">
                        <Video size={32} className="text-white" />
                    </div>
                    <div className="ripple-1"></div>
                    <div className="ripple-2"></div>
                </div>

                <h2 className="title">
                    Waiting for {userType === 'student' ? 'Doctor' : 'Student'}...
                </h2>
                <p className="description">
                    The call will start automatically when they join.
                </p>

                <button
                    onClick={onCancel}
                    className="cancel-button"
                >
                    <div className="cancel-icon">
                        <X size={16} />
                    </div>
                    <span>Cancel Call</span>
                </button>
>>>>>>> c356163 (video call ui setup)
            </div>
        </div>
    );
};

<<<<<<< HEAD
export default WaitingScreen;
=======
export default WaitingScreen;
>>>>>>> c356163 (video call ui setup)
