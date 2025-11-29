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
            </div>
        </div>
    );
};

export default WaitingScreen;