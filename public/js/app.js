// Utility functions
const api = {
    async get(endpoint) {
        try {
            const response = await fetch(`/api/${endpoint}`);
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    async post(endpoint, data) {
        try {
            const response = await fetch(`/api/${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
};

// Toast notification system
const toast = {
    show(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white ${
            type === 'error' ? 'bg-red-500' : 
            type === 'success' ? 'bg-green-500' : 
            'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
};

// Form validation helper
const validate = {
    email(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    
    required(value) {
        return value !== null && value !== undefined && value.toString().trim() !== '';
    },

    min(value, length) {
        return value.toString().length >= length;
    }
};

// Initialize Alpine.js components
document.addEventListener('alpine:init', () => {
    // Add your Alpine.js components here
});
