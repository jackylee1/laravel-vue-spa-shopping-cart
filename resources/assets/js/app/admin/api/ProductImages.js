export function update(request) {
    return axios.post('/api/admin/product_images/update', request);
}

export function updatePreview(request) {
    return axios.post('/api/admin/product_images/update_preview', request);
}