export function initialize(store, router) {
  router.beforeEach((to, from, next) => {
    next();
  });

  axios.interceptors.response.use(null, (error) => {
    if (error.response.status === 401) {
      store.commit('logout');
      router.push('/login');
    }

    return Promise.reject(error);
  });

  if (store.getters.currentUser) {
    setAuthorization(store.getters.currentUser.token);
  }
}

export function setAuthorization(token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}