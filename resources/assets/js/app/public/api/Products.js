export function get(page = 1, params = {}) {
  return axios.get(`/api/products?page=${page}`, {
    params: params
  });
}

export function view(slug) {
  return axios.get(`/api/product/view/${slug}`);
}