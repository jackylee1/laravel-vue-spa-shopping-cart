export function create(request) {
    return axios.post('/api/subscribe', request);
}