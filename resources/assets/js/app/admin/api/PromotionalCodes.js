export function destroy(id) {
  return axios.delete(`/api/admin/promotional_codes/${id}`);
}

export function get(page = 1, params = null) {
  return axios.get(`/api/admin/promotional_codes?page=${page}`, {
    params: params
  });
}

export function show(id) {
  return axios.get(`/api/admin/promotional_codes/${id}`);
}

export function update(id, request) {
  return axios.patch(`/api/admin/promotional_codes/${id}`, request);
}

export function create(request) {
  return axios.post('/api/admin/promotional_codes', request);
}

export function send(params = {}) {
  return axios.post('/api/admin/users/send_promotional_code', params)
}

export function getFree() {
  return axios.post('/api/admin/promotional_codes/get_free');
}