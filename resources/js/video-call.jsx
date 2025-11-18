import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './components/App';

// Global state to hold initialization config
let initializationConfig = null;
let rootInstance = null;
let mountAttempted = false;

// Exported function for external initialization (called from blade loader)
window.initializeVideoCallApp = (containerId, streamConfig, consultation, userType) => {
    console.log('initializeVideoCallApp called with:', { containerId, streamConfig, consultation, userType });
    
    if (mountAttempted) {
        console.log('App already mounted, skipping duplicate mount');
        return rootInstance;
    }
    
    const config = {
        streamConfig,
        consultation,
        userType
    };
    
    initializationConfig = config;
    const container = document.getElementById(containerId);
    if (!container) {
        console.error(`Container #${containerId} not found`);
        return null;
    }
    
    console.log('video-call entry: mounting to #' + containerId);
    try {
        const root = createRoot(container);
        root.render(<App initialConfig={initializationConfig} />);
        rootInstance = root;
        mountAttempted = true;
        return rootInstance;
    } catch (err) {
        console.error('Failed to mount React app:', err);
        return null;
    }
};

// Fallback: Try mount without config if not already mounted
const attemptFallbackMount = () => {
    if (mountAttempted || initializationConfig) return;
    
    console.log('video-call entry: attempting fallback mount');
    const container = document.getElementById('video-call-root');
    if (!container) return;
    
    try {
        const root = createRoot(container);
        root.render(<App />);
        rootInstance = root;
        mountAttempted = true;
    } catch (err) {
        console.error('Fallback mount failed:', err);
    }
};

// Wait for DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attemptFallbackMount);
} else {
    // DOM already ready - but give initializeVideoCallApp a chance to run first
    setTimeout(attemptFallbackMount, 0);
}