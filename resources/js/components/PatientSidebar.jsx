import React, { useState } from 'react';
import {
    Drawer,
    Box,
    Typography,
    IconButton,
    Divider,
    TextField,
    Button,
    Stack,
    Chip,
    Paper,
    useTheme,
    useMediaQuery
} from '@mui/material';
import {
    Save as SaveIcon,
    Download as DownloadIcon,
    Close as CloseIcon,
    Medication as PillIcon,
    CalendarMonth as CalendarIcon,
    Person as PersonIcon,
    MedicalServices as SymptomsIcon
} from '@mui/icons-material';
import { saveNotes } from '../services/api';

const PatientSidebar = ({ consultation, isOpen, onClose }) => {
    const [notes, setNotes] = useState(consultation.doctor_notes || '');
    const [saving, setSaving] = useState(false);
    const theme = useTheme();
    const isMobile = useMediaQuery(theme.breakpoints.down('md'));

    const handleSaveNotes = async () => {
        setSaving(true);
        try {
            await saveNotes(consultation.id, notes);
            // Show success message (could add a Snackbar here later)
        } catch (error) {
            console.error('Failed to save notes:', error);
        } finally {
            setSaving(false);
        }
    };

    const downloadNotes = () => {
        const element = document.createElement('a');
        const file = new Blob([notes], { type: 'text/plain' });
        element.href = URL.createObjectURL(file);
        element.download = `consultation-${consultation.id}-notes.txt`;
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    };

    const addPrescriptionTemplate = () => {
        const template = '\n\n--- PRESCRIPTION ---\n• Medication: \n• Dosage: \n• Frequency: \n• Duration: ';
        setNotes(prev => prev + template);
    };

    const addFollowupTemplate = () => {
        const nextWeek = new Date();
        nextWeek.setDate(nextWeek.getDate() + 7);
        const template = `\n\n--- FOLLOW-UP ---\nRecommended follow-up: ${nextWeek.toLocaleDateString()}\nReason: `;
        setNotes(prev => prev + template);
    };

    const calculateAge = (dob) => {
        if (!dob) return 'N/A';
        return new Date().getFullYear() - new Date(dob).getFullYear();
    };

    return (
        <Drawer
            anchor="right"
            open={isOpen}
            onClose={onClose}
            variant={isMobile ? "temporary" : "persistent"}
            PaperProps={{
                sx: {
                    width: 320,
                    bgcolor: 'background.paper',
                    backdropFilter: 'blur(12px)',
                    borderLeft: '1px solid rgba(255, 255, 255, 0.1)',
                    boxShadow: '-4px 0 20px rgba(0, 0, 0, 0.5)',
                    height: '100%',
                    mt: isMobile ? 0 : 0, // Adjust if needed for header
                    zIndex: 1100
                }
            }}
        >
            <Box sx={{ display: 'flex', flexDirection: 'column', height: '100%' }}>
                {/* Header */}
                <Box sx={{ p: 2, display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderBottom: '1px solid rgba(255, 255, 255, 0.1)' }}>
                    <Typography variant="h6" sx={{ fontWeight: 600, display: 'flex', alignItems: 'center', gap: 1 }}>
                        <PersonIcon color="primary" />
                        Patient Details
                    </Typography>
                    <IconButton onClick={onClose} size="small">
                        <CloseIcon />
                    </IconButton>
                </Box>

                <Box sx={{ flex: 1, overflowY: 'auto', p: 2 }}>
                    {/* Patient Info */}
                    <Paper variant="outlined" sx={{ p: 2, mb: 3, bgcolor: 'rgba(255, 255, 255, 0.03)', borderColor: 'rgba(255, 255, 255, 0.1)' }}>
                        <Stack spacing={1.5}>
                            <Box sx={{ display: 'flex', justifyContent: 'space-between' }}>
                                <Typography variant="body2" color="text.secondary">Name</Typography>
                                <Typography variant="body2" fontWeight={600}>{consultation.student.user.name}</Typography>
                            </Box>
                            <Divider sx={{ borderColor: 'rgba(255, 255, 255, 0.05)' }} />
                            <Box sx={{ display: 'flex', justifyContent: 'space-between' }}>
                                <Typography variant="body2" color="text.secondary">Age</Typography>
                                <Typography variant="body2" fontWeight={600}>{calculateAge(consultation.student.user.date_of_birth)} years</Typography>
                            </Box>
                            <Divider sx={{ borderColor: 'rgba(255, 255, 255, 0.05)' }} />
                            <Box sx={{ display: 'flex', justifyContent: 'space-between' }}>
                                <Typography variant="body2" color="text.secondary">Gender</Typography>
                                <Typography variant="body2" fontWeight={600} sx={{ textTransform: 'capitalize' }}>
                                    {consultation.student.user.gender || 'N/A'}
                                </Typography>
                            </Box>
                        </Stack>
                    </Paper>

                    {/* Symptoms */}
                    <Box sx={{ mb: 3 }}>
                        <Typography variant="subtitle2" sx={{ mb: 1, display: 'flex', alignItems: 'center', gap: 1, color: 'text.secondary' }}>
                            <SymptomsIcon fontSize="small" color="warning" />
                            Reported Symptoms
                        </Typography>
                        <Paper
                            variant="outlined"
                            sx={{
                                p: 2,
                                bgcolor: 'rgba(245, 158, 11, 0.1)',
                                borderColor: 'rgba(245, 158, 11, 0.3)',
                                color: '#fbbf24'
                            }}
                        >
                            <Typography variant="body2">
                                {consultation.symptoms}
                            </Typography>
                        </Paper>
                    </Box>

                    {/* Notes */}
                    <Box sx={{ mb: 3 }}>
                        <Typography variant="subtitle2" sx={{ mb: 1, color: 'text.secondary' }}>
                            Consultation Notes
                        </Typography>
                        <TextField
                            multiline
                            rows={8}
                            fullWidth
                            placeholder="Enter diagnosis, prescription, and recommendations..."
                            value={notes}
                            onChange={(e) => setNotes(e.target.value)}
                            variant="outlined"
                            sx={{
                                mb: 2,
                                '& .MuiOutlinedInput-root': {
                                    bgcolor: 'rgba(0, 0, 0, 0.2)',
                                    '& fieldset': { borderColor: 'rgba(255, 255, 255, 0.1)' },
                                    '&:hover fieldset': { borderColor: 'rgba(255, 255, 255, 0.2)' },
                                }
                            }}
                        />
                        <Stack direction="row" spacing={1}>
                            <Button
                                variant="contained"
                                color="primary"
                                startIcon={<SaveIcon />}
                                onClick={handleSaveNotes}
                                disabled={saving}
                                fullWidth
                            >
                                {saving ? 'Saving...' : 'Save'}
                            </Button>
                            <Button
                                variant="outlined"
                                color="info"
                                onClick={downloadNotes}
                                sx={{ minWidth: 48, px: 0 }}
                            >
                                <DownloadIcon />
                            </Button>
                        </Stack>
                    </Box>

                    {/* Quick Actions */}
                    <Box>
                        <Typography variant="subtitle2" sx={{ mb: 1, color: 'text.secondary' }}>
                            Quick Actions
                        </Typography>
                        <Stack direction="row" spacing={1}>
                            <Button
                                variant="outlined"
                                color="secondary"
                                startIcon={<PillIcon />}
                                onClick={addPrescriptionTemplate}
                                size="small"
                                fullWidth
                                sx={{ borderColor: 'rgba(255, 255, 255, 0.2)', color: 'text.primary' }}
                            >
                                Prescribe
                            </Button>
                            <Button
                                variant="outlined"
                                color="secondary"
                                startIcon={<CalendarIcon />}
                                onClick={addFollowupTemplate}
                                size="small"
                                fullWidth
                                sx={{ borderColor: 'rgba(255, 255, 255, 0.2)', color: 'text.primary' }}
                            >
                                Follow-up
                            </Button>
                        </Stack>
                    </Box>
                </Box>
            </Box>
        </Drawer>
    );
};

export default PatientSidebar;