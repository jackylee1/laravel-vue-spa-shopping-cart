export function destroy(id) {
  return axios.delete(`/api/admin/users/${id}`);
}

export function get(page = 1, params = {}) {
  return axios.get(`/api/admin/users?page=${page}`, {
    params: params
  })
}

export function show(id) {
  return axios.get(`/api/admin/users/${id}`);
}

export function handlePromotionalCode(params = {}) {
  return axios.post('/api/admin/users/handle_promotional_code', params)
}

export function create(request) {
  return axios.post('/api/admin/users', request);
}

export function update(id, request) {
  return axios.patch(`/api/admin/users/${id}`, request);
}