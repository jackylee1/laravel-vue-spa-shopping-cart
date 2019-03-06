export function destroy(id) {
    return axios.delete(`/api/admin/types/${id}`);
}

export function get() {
    return axios.get('/api/admin/types');
}

export function show(id) {
    return axios.get(`/api/admin/types/${id}`);
}

export function update(id, request) {
    return axios.patch(`/api/admin/types/${id}`, request);
}

export function create(request) {
    return axios.post('/api/admin/types', request);
}

export function handleFilter(params = {}) {
    return axios.post('/api/admin/types/handle_filter', params)
}