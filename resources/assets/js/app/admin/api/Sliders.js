export function get() {
  return axios.get('/api/admin/sliders');
}
export function show(id) {
  return axios.get(`/api/admin/sliders/${id}`);
}
export function destroy(id) {
  return axios.delete(`/api/admin/sliders/${id}`);
}

export function update(id, request) {
  return axios.post(`/api/admin/sliders/update`, request);
}

export function create(request) {
  return axios.post('/api/admin/sliders', request);
}