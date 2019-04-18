export default {
    data() {
        return {
            typeAlerts: 'danger',
            alerts: null
        }
    },
    methods: {
        clearAlerts: function () {
            this.alerts = null;
        }
    }
}