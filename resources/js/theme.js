import { createTheme } from '@mui/material/styles';

const theme = createTheme({
    palette: {
        mode: 'dark',
        primary: {
            main: '#3b82f6', // Blue-500
        },
        secondary: {
            main: '#10b981', // Emerald-500
        },
        error: {
            main: '#ef4444', // Red-500
        },
        background: {
            default: '#0f172a', // Slate-900
            paper: 'rgba(30, 41, 59, 0.7)', // Slate-800 with opacity
        },
        text: {
            primary: '#f8fafc', // Slate-50
            secondary: '#94a3b8', // Slate-400
        },
    },
    typography: {
        fontFamily: '"Inter", "Roboto", "Helvetica", "Arial", sans-serif',
        h4: {
            fontWeight: 600,
            letterSpacing: '-0.02em',
        },
        h5: {
            fontWeight: 600,
        },
        h6: {
            fontWeight: 600,
        },
    },
    shape: {
        borderRadius: 16,
    },
    components: {
        MuiCssBaseline: {
            styleOverrides: {
                body: {
                    background: 'linear-gradient(135deg, #0f172a 0%, #020617 100%)',
                    minHeight: '100vh',
                    overflow: 'hidden',
                },
            },
        },
        MuiPaper: {
            styleOverrides: {
                root: {
                    backdropFilter: 'blur(12px)',
                    backgroundImage: 'none',
                    border: '1px solid rgba(255, 255, 255, 0.1)',
                    boxShadow: '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
                },
            },
        },
        MuiButton: {
            styleOverrides: {
                root: {
                    textTransform: 'none',
                    fontWeight: 600,
                    borderRadius: 12,
                    padding: '10px 24px',
                },
                contained: {
                    boxShadow: '0 4px 14px 0 rgba(0, 0, 0, 0.39)',
                },
            },
        },
        MuiIconButton: {
            styleOverrides: {
                root: {
                    backdropFilter: 'blur(4px)',
                    backgroundColor: 'rgba(255, 255, 255, 0.05)',
                    border: '1px solid rgba(255, 255, 255, 0.1)',
                    '&:hover': {
                        backgroundColor: 'rgba(255, 255, 255, 0.15)',
                    },
                },
            },
        },
    },
});

export default theme;
