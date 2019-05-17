export function update(id, request) {
  return axios.patch(`/api/user/${id}`, request);
}

export function logout() {
  return axios.post('/api/auth/logout');
}

export function registration(request) {
  return axios.post('/api/user/registration', request);
}

export function sendResetPassword(request) {
  return axios.post('/api/user/send_reset_password', request);
}

export function resetPassword(request) {
  return axios.post('/api/user/reset_password', request);
}