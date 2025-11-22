import React, { useState, useEffect } from 'react';
import { PhoneOff, Menu, X, Clock, Users } from 'lucide-react';

const CallHeader = ({ consultation, userType, onEndCall, onToggleSidebar, sidebarOpen }) => {
    const [isMobile, setIsMobile] = useState(false);

    useEffect(() => {
        const checkMobile = () => {
            setIsMobile(window.innerWidth < 768);
        };

        checkMobile();
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);

    if (!consultation) {
        return <div className="bg-gradient-to-r from-slate-900 to-slate-800 border-b border-slate-700 px-6 py-4">Loading...</div>;
    }

    const otherPerson = userType === 'student' 
        ? consultation.doctor 
        : consultation.student?.user;
    
    const otherPersonName = otherPerson?.name || 'Unknown';

    if (isMobile) {
        return (
            <div className="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border-b border-slate-700 px-4 py-3 shadow-lg">
                <div className="flex items-center justify-between gap-2">
                    {/* Left: Person Info - Compact */}
                    <div className="flex items-center gap-2 min-w-0 flex-1">
                        <div className={`w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ring-2 text-sm ${
                            userType === 'student' 
                                ? 'bg-gradient-to-br from-blue-500 to-blue-600 ring-blue-400' 
                                : 'bg-gradient-to-br from-green-500 to-green-600 ring-green-400'
                        }`}>
                            {userType === 'student' ? '👨‍⚕️' : '🎓'}
                        </div>
                        <div className="min-w-0 flex-1">
                            <h2 className="text-sm font-bold text-white truncate">
                                {userType === 'student' ? 'Dr.' : ''} {otherPersonName}
                            </h2>
                            <div className="flex items-center gap-1 text-xs text-slate-300">
                                <div className="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                                <span>Connected</span>
                            </div>
                        </div>
                    </div>

                    {/* Right: End Call Button - Compact */}
                    <button
                        onClick={onEndCall}
                        className="bg-red-600 hover:bg-red-700 text-white p-2.5 rounded-lg hover:shadow-lg transition-all duration-200 flex-shrink-0 group"
                        title="End call"
                    >
                        <PhoneOff size={20} className="group-hover:scale-110 transition-transform" />
                    </button>
                </div>
            </div>
        );
    }

    // Desktop View
    return (
        <div className="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border-b border-slate-700 px-6 py-4 shadow-lg">
            <div className="flex items-center justify-between">
                {/* Left: Person Info */}
                <div className="flex items-center space-x-4 min-w-0 flex-1">
                    <div className={`w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 ring-2 ${
                        userType === 'student' 
                            ? 'bg-gradient-to-br from-blue-500 to-blue-600 ring-blue-400' 
                            : 'bg-gradient-to-br from-green-500 to-green-600 ring-green-400'
                    }`}>
                        <span className="text-lg">
                            {userType === 'student' ? '👨‍⚕️' : '🎓'}
                        </span>
                    </div>
                    <div className="min-w-0 flex-1">
                        <h2 className="text-lg font-bold text-white truncate">
                            Video Consultation
                        </h2>
                        <p className="text-sm text-slate-300 truncate">
                            With {userType === 'student' ? 'Dr.' : ''} {otherPersonName}
                        </p>
                    </div>
                </div>

                {/* Center: Status & Duration */}
                <div className="hidden md:flex items-center space-x-6 px-6">
                    <div className="flex items-center space-x-2 text-slate-300">
                        <div className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span className="text-sm font-medium">Connected</span>
                    </div>
                </div>
                
                {/* Right: Controls */}
                <div className="flex items-center space-x-3">
                    {/* Sidebar Toggle (Doctor only) */}
                    {userType === 'doctor' && (
                        <button
                            onClick={onToggleSidebar}
                            className="p-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-all duration-200 lg:hidden"
                            title={sidebarOpen ? 'Hide sidebar' : 'Show sidebar'}
                        >
                            {sidebarOpen ? <X size={20} /> : <Menu size={20} />}
                        </button>
                    )}
                    
                    {/* End Call Button */}
                    <button
                        onClick={onEndCall}
                        className="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg hover:shadow-lg transition-all duration-200 flex items-center space-x-2 font-medium group"
                        title="End call"
                    >
                        <PhoneOff size={18} className="group-hover:scale-110 transition-transform" />
                        <span className="hidden sm:inline">End Call</span>
                    </button>
                </div>
            </div>
        </div>
    );
};

export default CallHeader;