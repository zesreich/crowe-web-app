// API Configuration for Crowe HSY
// Backend server URL configuration

// Backend API Base URL
// Vercel: https://your-project.vercel.app veya custom domain
// Production: https://www.crowehsy.com
// Development: http://localhost:3000 (Vercel dev)
window.API_BASE_URL = window.API_BASE_URL || 'https://www.crowehsy.com';

// B10 Excel Generator endpoint (Vercel serverless function)
window.B10_EXCEL_API_URL = window.API_BASE_URL + '/api/generate_b10_excel';

// Helper function to get API URL
window.getApiUrl = function(endpoint) {
    const baseUrl = window.API_BASE_URL || 'https://www.crowehsy.com';
    // Remove trailing slash from base URL
    const cleanBaseUrl = baseUrl.replace(/\/$/, '');
    // Remove leading slash from endpoint
    const cleanEndpoint = endpoint.replace(/^\//, '');
    return cleanBaseUrl + '/' + cleanEndpoint;
};

// Vercel serverless function endpoint helper
window.getVercelApiUrl = function(functionName) {
    const baseUrl = window.API_BASE_URL || 'https://www.crowehsy.com';
    const cleanBaseUrl = baseUrl.replace(/\/$/, '');
    return cleanBaseUrl + '/api/' + functionName;
};

