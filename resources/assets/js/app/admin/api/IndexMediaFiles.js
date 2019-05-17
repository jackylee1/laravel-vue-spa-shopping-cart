export function destroy(id) {
  return axios.delete(`/api/admin/index_media_files/${id}`);
}

export function get() {
  return axios.get('/api/admin/index_media_files');
}

export function show(id) {
  return axios.get(`/api/admin/index_media_files/${id}`);
}

export function update(id, request) {
  return axios.patch(`/api/admin/index_media_files/${id}`, request);
}

export function create(request) {
  return axios.post('/api/admin/index_media_files', request);
}