export function update(id, request) {
    return axios.patch(`/api/user/${id}`, request);
}

export function registration(request) {
    return axios.post('/api/user/registration', request);
}