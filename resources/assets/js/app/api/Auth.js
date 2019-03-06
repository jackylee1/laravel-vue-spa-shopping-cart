export function login(request) {
    return axios.post('/api/auth/login', request);
}