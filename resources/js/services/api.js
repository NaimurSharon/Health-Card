import axios from 'axios';

const api = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
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