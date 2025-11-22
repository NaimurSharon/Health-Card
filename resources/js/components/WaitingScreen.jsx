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
            </div>
        </div>
    );
};

export default WaitingScreen;
