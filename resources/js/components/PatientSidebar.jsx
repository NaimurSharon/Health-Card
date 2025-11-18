import React, { useState } from 'react';
import { Save, Download, X, Pill, Calendar } from 'lucide-react';
import { saveNotes } from '../services/api';

const PatientSidebar = ({ consultation, isOpen, onClose }) => {
    const [notes, setNotes] = useState(consultation.doctor_notes || '');
    const [saving, setSaving] = useState(false);

    const handleSaveNotes = async () => {
        setSaving(true);
        try {
            await saveNotes(consultation.id, notes);
            // Show success message
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

    if (!isOpen) return null;

    return (
        <div className="fixed inset-y-0 right-0 w-80 bg-white border-l border-gray-200 z-30 lg:relative lg:z-0">
            {/* Mobile close button */}
            <div className="lg:hidden p-3 border-b border-gray-200 bg-gray-50">
                <button
                    onClick={onClose}
                    className="flex items-center justify-between w-full text-left"
                >
                    <h3 className="font-semibold text-gray-900">Patient Information</h3>
                    <X size={16} className="text-gray-500" />
                </button>
            </div>

            <div className="h-full overflow-y-auto">
                {/* Patient Information */}
                <div className="p-4 border-b border-gray-200">
                    <h3 className="font-semibold text-gray-900 mb-3 flex items-center">
                        <span className="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Patient Information
                    </h3>
                    <div className="space-y-2 text-sm">
                        <div className="flex justify-between">
                            <span className="text-gray-600">Name:</span>
                            <span className="font-semibold">{consultation.student.user.name}</span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-gray-600">Age:</span>
                            <span className="font-semibold">
                                {consultation.student.user.date_of_birth 
                                    ? new Date().getFullYear() - new Date(consultation.student.user.date_of_birth).getFullYear()
                                    : 'N/A'
                                } years
                            </span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-gray-600">Gender:</span>
                            <span className="font-semibold capitalize">
                                {consultation.student.user.gender || 'N/A'}
                            </span>
                        </div>
                    </div>
                </div>

                {/* Symptoms */}
                <div className="p-4 border-b border-gray-200">
                    <h3 className="font-semibold text-gray-900 mb-2">Reported Symptoms</h3>
                    <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p className="text-sm text-gray-700">{consultation.symptoms}</p>
                    </div>
                </div>

                {/* Notes */}
                <div className="p-4 border-b border-gray-200">
                    <h3 className="font-semibold text-gray-900 mb-2">Consultation Notes</h3>
                    <textarea
                        value={notes}
                        onChange={(e) => setNotes(e.target.value)}
                        placeholder="Enter diagnosis, prescription, and recommendations..."
                        className="w-full h-48 px-3 py-2 border border-gray-300 rounded-lg text-sm resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    />
                    
                    <div className="flex space-x-2 mt-3">
                        <button
                            onClick={handleSaveNotes}
                            disabled={saving}
                            className="flex-1 bg-green-600 text-white py-2 px-3 rounded-lg hover:bg-green-700 transition-colors text-sm flex items-center justify-center disabled:opacity-50"
                        >
                            <Save size={16} className="mr-1" />
                            {saving ? 'Saving...' : 'Save'}
                        </button>
                        <button
                            onClick={downloadNotes}
                            className="bg-blue-600 text-white py-2 px-3 rounded-lg hover:bg-blue-700 transition-colors text-sm flex items-center justify-center"
                        >
                            <Download size={16} />
                        </button>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="p-4">
                    <h3 className="font-semibold text-gray-900 mb-3">Quick Actions</h3>
                    <div className="grid grid-cols-2 gap-2">
                        <button
                            onClick={addPrescriptionTemplate}
                            className="bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors text-sm flex items-center justify-center"
                        >
                            <Pill size={16} className="mr-1" />
                            Prescribe
                        </button>
                        <button
                            onClick={addFollowupTemplate}
                            className="bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors text-sm flex items-center justify-center"
                        >
                            <Calendar size={16} className="mr-1" />
                            Follow-up
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PatientSidebar;