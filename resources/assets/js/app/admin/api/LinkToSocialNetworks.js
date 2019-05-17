export function get() {
  return axios.get('/api/admin/link_to_social_networks');
}
export function show(id) {
  return axios.get(`/api/admin/link_to_social_networks/${id}`);
}
export function destroy(id) {
  return axios.delete(`/api/admin/link_to_social_networks/${id}`);
}

export function update(id, request) {
  return axios.post(`/api/admin/link_to_social_networks/update`, request);
}

export function create(request) {
  return axios.post('/api/admin/link_to_social_networks', request);
}