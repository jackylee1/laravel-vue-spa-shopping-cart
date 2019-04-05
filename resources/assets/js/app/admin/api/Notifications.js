export function newNotifications() {
    return axios.post('/api/admin/new_notifications');
}