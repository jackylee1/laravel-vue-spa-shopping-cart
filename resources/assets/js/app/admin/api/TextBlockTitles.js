export function destroy(id) {
    return axios.delete(`/api/admin/text_block_titles/${id}`);
}

export function get() {
    return axios.get('/api/admin/text_block_titles');
}

export function show(id) {
    return axios.get(`/api/admin/text_block_titles/${id}`);
}

export function update(id, request) {
    return axios.patch(`/api/admin/text_block_titles/${id}`, request);
}

export function create(request) {
    return axios.post('/api/admin/text_block_titles', request);
}