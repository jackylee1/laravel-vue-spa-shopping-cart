<template>
    <div>
        <template v-if="errors.items !== undefined && errors.items.length">
            <div class="row col-sm-12">
                <div class="alert alert-danger" style="margin-top: 10px;min-width: 100%">
                    <span v-for="error in errors.items">{{ error.msg }}</span>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'VeeErrors'
    }
</script>