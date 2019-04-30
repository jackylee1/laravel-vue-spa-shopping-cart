import Echo from 'laravel-echo';

export function leaveEvents() {
    if (window.Echo !== undefined && window.Echo !== null) {
        window.Echo.leave('messages');
    }
}

export function listeningEvents(store) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
        auth: {
            headers: {
                'Authorization': `Bearer ${store.getters.currentUser.token}`
            }
        }
    });

    window.Echo.private('messages').listen('AdminEvent', (message) => {
        switch (message.model) {
            case 'subscribe':
                store.commit('updateNewSubscribes', store.getters.newSubscribes + 1);
                if (store.getters.subscribes.data !== undefined) {
                    let subscribes = store.getters.subscribes;
                    subscribes.data.unshift(message.data);
                    store.commit('updateSubscribes', subscribes);
                }
                break;
            case 'order':
                store.commit('updateNewOrders', store.getters.newOrders + 1);
                if (store.getters.orders.data !== undefined) {
                    let orders = store.getters.orders;
                    orders.data.unshift(message.data);
                    store.commit('updateOrders', orders);
                }
                break;
        }
    });
}