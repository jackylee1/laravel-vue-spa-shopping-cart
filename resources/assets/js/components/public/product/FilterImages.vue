<template>
    <div>
        <template v-for="(itemImage, index) in this.getImages()">
            <img :style="(index !== 0) ? 'margin-left: 5px' : ''" class="item_logo" :src="`/app/public/images/filter/${itemImage}`" alt="">
        </template>
    </div>
</template>

<script>
    export default {
        name: 'FilterImages',
        props: ['product'],
        computed: {
            'filters': function () {
                return this.$store.getters.filters;
            }
        },
        methods: {
            'getImages': function () {
                let images;
                let filters;

                filters = [];
                this.product.filters.forEach((item) => {
                    item.filters.forEach((filter) => {
                        filters.push(filter.filter_id);
                    });
                });
                filters = _.uniq(filters);
                images = [];
                filters.forEach((filter) => {
                    let index = this.filters.findIndex((item) => item.id === filter && item.parent_id !== 0);
                    if (index !== -1) {
                        let image = this.filters[index].image_preview;
                        if (image !== null) {
                            images.push(image);
                        }
                    }
                });

                return images;
            }
        }
    }
</script>