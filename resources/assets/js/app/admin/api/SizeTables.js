export function destroy(id) {
    return axios.delete(`/api/admin/size_tables/${id}`);
}

export function get() {
    return axios.get('/api/admin/size_tables');
}

export function show(id) {
    return axios.get(`/api/admin/size_tables/${id}`);
}

export function update(id, request) {
    return axios.patch(`/api/admin/size_tables/${id}`, request);
}

export function create(request) {
    return axios.post('/api/admin/size_tables', request);
}