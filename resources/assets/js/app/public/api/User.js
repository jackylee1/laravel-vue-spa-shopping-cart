export function update(id, request) {
    return axios.patch(`/api/user/${id}`, request);
}