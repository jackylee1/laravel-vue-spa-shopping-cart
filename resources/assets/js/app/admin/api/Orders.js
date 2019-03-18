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

export function addProduct(request) {
    return axios.post('/api/admin/orders/add_product', request);
}

export function deleteProduct(request) {
    return axios.post('/api/admin/orders/delete_product', request);
}

export function deleteStatus(request) {
    return axios.post('/api/admin/orders/delete_status', request);
}

export function sendStatus(request) {
    return axios.post('/api/admin/orders/send_status', request);
}

export function updateReadStatus(request) {
    return axios.post('/api/admin/orders/update_read_status', request);
}

export function update(id, request) {
    return axios.patch(`/api/admin/orders/${id}`, request);
}

export function create(request) {
    return axios.post('/api/admin/orders', request);
}