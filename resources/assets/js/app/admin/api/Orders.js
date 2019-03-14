export function get(page = 1, params = {}) {
    return axios.get(`/api/admin/orders?page=${page}`, {
        params: params
    });
}
export function show(id) {
    return axios.get(`/api/admin/orders/${id}`);
}
export function destroy(id) {
    return axios.delete(`/api/admin/orders/${id}`);
}

export function update(id, request) {
    return axios.patch(`/api/admin/orders/${id}`, request);
}

export function create(request) {
    return axios.post('/api/admin/orders', request);
}