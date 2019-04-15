<template>
    <div>
        <div v-if="alerts !== null" :class="`alert alert-${type} alert-dismissible fade show`" role="alert">
            <template v-if="alerts instanceof Object">
                <template v-for="errors in alerts">
                    <template v-for="message in errors">
                        <strong>{{message}}</strong><br>
                    </template>
                </template>
            </template>

            <template v-else="alerts instanceof String">
                <strong>{{alerts}}</strong>
            </template>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Errors',
        props: ['alerts', 'type']
    }
</script>