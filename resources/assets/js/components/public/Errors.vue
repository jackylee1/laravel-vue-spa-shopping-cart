<template>
    <div>
        <div @click="clearAlerts" v-if="alerts !== null" :class="`alert alert-${type} alert-dismissible fade show`" role="alert">
            <template v-if="alerts instanceof Object">
                <template v-for="errors in alerts">
                    <template v-for="message in errors">
                        <p>{{message}}</p>
                    </template>
                </template>
            </template>

            <template v-if="typeof alerts === 'string'">
                <p>{{alerts}}</p>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Errors',
        props: ['alerts', 'type'],
        methods: {
            clearAlerts: function () {
                this.$emit('clearAlerts');
            }
        }
    }
</script>