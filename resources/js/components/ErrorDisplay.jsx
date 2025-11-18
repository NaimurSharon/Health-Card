import React from 'react';

const ErrorDisplay = ({ message, onRetry }) => {
    return (
        <div className="flex items-center justify-center h-screen bg-gray-900">
            <div className="text-center max-w-sm">
                <div className="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i className="fas fa-exclamation-triangle text-xl text-white"></i>
                </div>
                <h3 className="text-lg font-semibold text-white mb-2">Error</h3>
                <p className="text-gray-300 mb-4">{message}</p>
                {onRetry && (
                    <button
                        onClick={onRetry}
                        className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <i className="fas fa-redo mr-2"></i>Retry
                    </button>
                )}
            </div>
        </div>
    );
};

export default ErrorDisplay;
