export function destroy(id) {
  return axios.delete(`/api/admin/utf_records/${id}`);
}

export function get() {
  return axios.get('/api/admin/utf_records');
}

export function show(id) {
  return axios.get(`/api/admin/utf_records/${id}`);
}

export function update(id, request) {
  return axios.patch(`/api/admin/utf_records/${id}`, request);
}

export function create(request) {
  return axios.post('/api/admin/utf_records', request);
}