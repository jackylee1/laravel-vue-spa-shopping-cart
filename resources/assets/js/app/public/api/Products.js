export function get(page = 1, params = {}) {
    return axios.get(`/api/products?page=${page}`, {
        params: params
    });
}