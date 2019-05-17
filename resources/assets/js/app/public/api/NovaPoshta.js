export function areas(response = {}) {
  return axios.post('/api/nova_poshta/areas', response);
}

export function cities(response = {}) {
  return axios.post('/api/nova_poshta/cities', response);
}

export function warehouses(response = {}) {
  return axios.post('/api/nova_poshta/warehouses', response);
}