import axios from "axios";

// # Base API instance
const api = axios.create({
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
    },
});

// # Attach CSRF token
api.interceptors.request.use((config) => {
    const token = document
        .querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
        ?.content;

    if (token) {
        config.headers["X-CSRF-TOKEN"] = token;
    }

    return config;
});

export default api;
