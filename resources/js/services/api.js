import axios from 'axios';

<<<<<<< HEAD
const api = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
=======
// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const api = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
>>>>>>> c356163 (video call ui setup)
    }
});

export const saveNotes = async (consultationId, notes) => {
    const response = await api.post(`/api/doctor/consultations/${consultationId}/notes`, {
        notes
    });
    return response.data;
};

export const endCall = async (consultationId, duration = 0) => {
    const response = await api.post(`/api/video-call/${consultationId}/end`, {
        duration
    });
    return response.data;
};

export default api;