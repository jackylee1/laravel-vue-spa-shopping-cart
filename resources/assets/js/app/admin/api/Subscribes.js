export function destroy(id) {
    return axios.delete(`/api/admin/subscribes/${id}`);
}

export function get(page = 1, params = {}) {
    return axios.get(`/api/admin/subscribes?page=${page}`, {
        params: params
    })
}

export function show(id) {
    return axios.get(`/api/admin/subscribes/${id}`);
}

export function create(request) {
    return axios.post('/api/admin/subscribes', request);
}

export function update(id, request) {
    return axios.patch(`/api/admin/subscribes/${id}`, request);
}

export function updateReadStatus(request) {
    return axios.post('/api/admin/subscribes/update_read_status', request);
}