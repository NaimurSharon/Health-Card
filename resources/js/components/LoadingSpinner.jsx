import React from 'react';

const LoadingSpinner = ({ message = "Loading..." }) => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-900">
            <div className="text-center">
                <div className="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p className="text-white text-lg font-semibold">{message}</p>
            </div>
        </div>
    );
};

export default LoadingSpinner;
