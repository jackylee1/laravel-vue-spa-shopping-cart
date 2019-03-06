export function get() {
    return axios.get('/api/admin/filters');
}

export function destroy(id) {
    return axios.delete(`/api/admin/filters/${id}`);
}

export function update(request) {
    return axios.post('/api/admin/filters/update', request);
}

export function create(request) {
    return axios.post('/api/admin/filters', request);
}