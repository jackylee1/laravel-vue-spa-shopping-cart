export function create(request) {
    return axios.post('/api/order/create', request);
}

export function get(page = 1, params = {}) {
    return axios.get(`/api/orders?page=${page}`, {
        params: params
    });
}

export function view(request) {
    return axios.post('/api/order', request);
}

export function byInOnOneClick(request) {
    return axios.post('/api/order/by_in_one_click', request);
}