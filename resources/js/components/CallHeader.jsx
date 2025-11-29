<<<<<<< HEAD
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
=======
import React from 'react';
import {
    AppBar,
    Toolbar,
    Typography,
    Box,
    IconButton,
    Avatar,
    Chip,
    useTheme,
    useMediaQuery
} from '@mui/material';
import {
    Menu as MenuIcon,
    Close as CloseIcon,
    Videocam as VideoIcon,
    Person as PersonIcon
} from '@mui/icons-material';

const CallHeader = ({ consultation, userType, onEndCall, onToggleSidebar, sidebarOpen }) => {
    const theme = useTheme();
    const isMobile = useMediaQuery(theme.breakpoints.down('sm'));
    const isDoctor = userType === 'doctor';

    // Determine who we are talking to
    const otherPerson = isDoctor ? consultation.student.user : consultation.doctor;
    const otherRole = isDoctor ? 'Student' : 'Dr.';

    return (
        <AppBar
            position="absolute"
            color="transparent"
            elevation={0}
            sx={{
                top: 0,
                left: 0,
                right: 0,
                zIndex: 1200,
                background: 'linear-gradient(to bottom, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%)',
                pointerEvents: 'none' // Let clicks pass through to video except for buttons
            }}
        >
            <Toolbar sx={{ justifyContent: 'space-between', pointerEvents: 'auto', py: 1 }}>
                {/* Left: Branding & Info */}
                <Box sx={{ display: 'flex', alignItems: 'center', gap: 2 }}>
                    <Box
                        sx={{
                            display: 'flex',
                            alignItems: 'center',
                            gap: 1,
                            bgcolor: 'rgba(255,255,255,0.1)',
                            backdropFilter: 'blur(10px)',
                            px: 2,
                            py: 0.5,
                            borderRadius: 10,
                            border: '1px solid rgba(255,255,255,0.1)'
                        }}
                    >
                        <VideoIcon sx={{ color: theme.palette.primary.main }} />
                        <Typography variant="subtitle2" sx={{ color: 'white', fontWeight: 600 }}>
                            HealthCare
                        </Typography>
                    </Box>

                    {!isMobile && (
                        <Box sx={{ display: 'flex', alignItems: 'center', gap: 1.5 }}>
                            <Box sx={{ width: 1, height: 24, bgcolor: 'rgba(255,255,255,0.2)' }} /> {/* Divider */}
                            <Avatar
                                src={otherPerson.profile_photo_url}
                                alt={otherPerson.name}
                                sx={{ width: 32, height: 32, border: '2px solid rgba(255,255,255,0.2)' }}
                            >
                                {otherPerson.name.charAt(0)}
                            </Avatar>
                            <Box>
                                <Typography variant="subtitle2" sx={{ color: 'white', lineHeight: 1.2 }}>
                                    {otherPerson.name}
                                </Typography>
                                <Typography variant="caption" sx={{ color: 'rgba(255,255,255,0.7)' }}>
                                    {otherRole}
                                </Typography>
                            </Box>
                        </Box>
                    )}
                </Box>

                {/* Right: Actions */}
                <Box sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
                    <Chip
                        label="Live"
                        color="error"
                        size="small"
                        sx={{
                            fontWeight: 700,
                            height: 24,
                            animation: 'pulse 2s infinite',
                            '@keyframes pulse': {
                                '0%': { opacity: 1 },
                                '50%': { opacity: 0.5 },
                                '100%': { opacity: 1 },
                            }
                        }}
                    />

                    {isDoctor && (
                        <IconButton
                            onClick={onToggleSidebar}
                            sx={{
                                color: 'white',
                                bgcolor: sidebarOpen ? 'rgba(59, 130, 246, 0.2)' : 'rgba(255,255,255,0.1)',
                                '&:hover': { bgcolor: 'rgba(255,255,255,0.2)' }
                            }}
                        >
                            {sidebarOpen ? <CloseIcon /> : <PersonIcon />}
                        </IconButton>
                    )}
                </Box>
            </Toolbar>
        </AppBar>
>>>>>>> c356163 (video call ui setup)
    );
};

export default CallHeader;