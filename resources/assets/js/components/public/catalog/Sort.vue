<template>
    <div>
        <div class="row titles_top">
            <div class="col-md-6 col-12 my-auto">
                <h1 class="category_title" v-if="currentCategory.name !== undefined">
                    {{currentCategory.name}}
                </h1>
            </div>
            <div class="col-md-3 col-6 sort righted my-auto">
                <p>Сортировка:</p>
            </div>
            <div class="col-md-3 col-6 my-auto">
                <select v-model="sort" class="form-control-sm custom-select">
                    <template v-for="option in options">
                        <option :value="option.value">{{option.name}}</option>
                    </template>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Sort',
        props: ['currentCategory'],
        data() {
            return {
                sort: 'all',
                options: [
                    {value: 'all', name: 'все товары'},
                    {value: 'from_cheap_to_expensive', name: 'от дешевых к дорогим'},
                    {value: 'from_expensive_to_cheap', name: 'от дорогих к дешевым'},
                    {value: 'popular', name: 'популярные'},
                    {value: 'new', name: 'новинки'},
                    {value: 'promotional', name: 'акционные'},
                ]
            }
        }
    }
</script>