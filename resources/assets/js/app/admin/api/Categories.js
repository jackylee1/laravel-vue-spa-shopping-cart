export function destroy(id) {
  return axios.delete(`/api/admin/categories/${id}`);
}

export function update(id, request) {
  return axios.patch(`/api/admin/categories/${id}`, request, {
    timeout: 0
  });
}

export function create(request) {
  return axios.post('/api/admin/categories', request, {
    timeout: 0
  });
}

export function handleFilter(params = {}) {
  return axios.post('/api/admin/categories/handle_filter', params)
}