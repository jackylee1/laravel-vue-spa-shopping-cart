export function get() {
    return axios.get('/api/admin/products');
}
export function show(id) {
    return axios.get(`/api/admin/products/${id}`);
}
export function destroy(id) {
    return axios.delete(`/api/admin/products/${id}`);
}

export function update(id, request) {
    return axios.patch(`/api/admin/products/${id}`, request);
}

export function create(request) {
    return axios.post('/api/admin/products', request);
}

export function uploadImages(request) {
    return axios.post('/api/admin/product_images/upload', request);
}

export function deleteImages(request) {
    return axios.post('/api/admin/product_images/delete', request);
}

export function addFilterToProduct(request) {
    return axios.post('/api/admin/products/add_filter', request);
}

export function removeFilterToProduct(request) {
    return axios.post('/api/admin/products/remove_filter', request);
}