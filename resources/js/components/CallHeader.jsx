import React from 'react';
import { PhoneOff, Menu, X } from 'lucide-react';

const CallHeader = ({ consultation, userType, onEndCall, onToggleSidebar, sidebarOpen }) => {
    const otherPerson = userType === 'student' 
        ? consultation.doctor 
        : consultation.student.user;

    return (
        <div className="bg-white border-b border-gray-200 px-4 py-3">
            <div className="flex items-center justify-between">
                <div className="flex items-center space-x-3 min-w-0 flex-1">
                    <div className={`w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${
                        userType === 'student' ? 'bg-blue-100' : 'bg-green-100'
                    }`}>
                        {userType === 'student' ? (
                            <span className="text-blue-600 text-sm">👨‍⚕️</span>
                        ) : (
                            <span className="text-green-600 text-sm">🎓</span>
                        )}
                    </div>
                    <div className="min-w-0 flex-1">
                        <h2 className="text-base font-bold text-gray-900 truncate">
                            Video Consultation
                        </h2>
                        <p className="text-sm text-gray-600 truncate">
                            With {userType === 'student' ? 'Dr.' : ''} {otherPerson.name}
                        </p>
                    </div>
                </div>
                
                <div className="flex items-center space-x-3">
                    <div className="text-right hidden sm:block">
                        <p className="text-xs text-gray-600">
                            ID: <span className="font-mono">{consultation.call_id}</span>
                        </p>
                    </div>
                    
                    {/* Sidebar Toggle (Doctor only) */}
                    {userType === 'doctor' && (
                        <button
                            onClick={onToggleSidebar}
                            className="p-2 text-gray-600 hover:text-gray-900 transition-colors lg:hidden"
                        >
                            {sidebarOpen ? <X size={20} /> : <Menu size={20} />}
                        </button>
                    )}
                    
                    <button
                        onClick={onEndCall}
                        className="bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-1"
                    >
                        <PhoneOff size={16} />
                        <span className="hidden sm:inline text-sm">End</span>
                    </button>
                </div>
            </div>
        </div>
    );
};

export default CallHeader;