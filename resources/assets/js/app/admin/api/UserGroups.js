export function destroy(id) {
    return axios.delete(`/api/admin/user_groups/${id}`);
}

export function get() {
    return axios.get('/api/admin/user_groups');
}

export function show(id, page) {
    return axios.get(`/api/admin/user_groups/${id}?page=${page}`);
}

export function actionHandler(params = {}) {
    return axios.post('/api/admin/user_groups/user_action_handler', params);
}

export function update(id, request) {
    return axios.patch(`/api/admin/user_groups/${id}`, request);
}

export function create(request) {
    return axios.post('/api/admin/user_groups', request);
}