export function create(request) {
    return axios.post('/api/favorite', request);
}

export function destroy(request) {
    return axios.post('/api/favorite/destroy', request);
}