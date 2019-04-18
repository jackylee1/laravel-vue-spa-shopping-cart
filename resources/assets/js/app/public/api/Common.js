export function get(request = {}) {
    return axios.post('/api/common', request);
}