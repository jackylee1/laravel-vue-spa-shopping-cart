export function get() {
  return axios.get('/api/admin/order_statuses');
}
export function show(id) {
  return axios.get(`/api/admin/order_statuses/${id}`);
}
export function destroy(id) {
  return axios.delete(`/api/admin/order_statuses/${id}`);
}

export function update(id, request) {
  return axios.patch(`/api/admin/order_statuses/${id}`, request);
}

export function create(request) {
  return axios.post('/api/admin/order_statuses', request);
}