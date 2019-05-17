export function get() {
  return axios.get('/api/admin/order_payment_methods');
}

export function show(id) {
  return axios.get(`/api/admin/order_payment_methods/${id}`);
}

export function destroy(id) {
  return axios.delete(`/api/admin/order_payment_methods/${id}`);
}

export function update(id, request) {
  return axios.patch(`/api/admin/order_payment_methods/${id}`, request);
}

export function create(request) {
  return axios.post('/api/admin/order_payment_methods', request);
}