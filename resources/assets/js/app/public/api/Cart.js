export function addProduct(request = {}) {
    return axios.post('/api/cart/add_product', request);
}

export function deleteProduct(request = {}) {
    return axios.post('/api/cart/delete_product', request);
}

export function updateQuantityProduct(request = {}) {
    return axios.post('/api/cart/update_quantity_product', request);
}

export function update(request) {
    return axios.post('/api/cart/update', request);
}