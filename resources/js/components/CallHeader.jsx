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
    );
};

export default CallHeader;