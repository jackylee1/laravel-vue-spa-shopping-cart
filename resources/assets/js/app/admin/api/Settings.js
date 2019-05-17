export function get() {
  return axios.post('/api/admin/settings');
}

export function update(request) {
  return axios.post('/api/admin/settings/update', request);
}