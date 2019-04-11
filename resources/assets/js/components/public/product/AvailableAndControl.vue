<template>
    <div>
        <div class="row">
            <div class="col-sm-6 variants">
            </div>
            <div class="col-sm-6 tablecell">
                <a href="#size_table">Таблица размеров</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 variations">
                <form class="form-horizontal " method="post" action="">
                    <div class="form-group">
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            <RenderAvailable :availableModels="product.available"
                                             :idAvailable="idAvailable"
                                             v-on:changeIdAvailable="changeIdAvailable"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 size_table_link">
                <a href="#size_table">Таблица размеров</a>
            </div>
        </div>
        <div class="row by_it">
            <div class="col-4 by_it_form">
                <div class="input-group spinner">
                    <input type="text" class="form-control"
                           :value="valueQuantity"
                           :min="minQuantity"
                           :max="maxQuantity">
                    <div class="input-group-btn-vertical">
                        <button class="btn btn-default" type="button">+</button>
                        <button class="btn btn-default" type="button">-</button>
                    </div>
                </div>
            </div>
            <div class="col-8 add_to_cart my-auto">
                <a href="#"><i class="fas fa-shopping-cart"></i>КУПИТЬ</a>
            </div>
        </div>
        <div class="row by_it">
            <div class="col-12 one_click my-auto">
                <a href="#">Купить в один клик</a>
            </div>
        </div>
        <div class="row like_it">
            <div class="col-md-12 like_it_icon my-auto">
                <a href="#" class="hrt"><i class="far fa-heart"></i> Добавить в избранное</a>
            </div>
        </div>
    </div>
</template>

<script>
    import RenderAvailable from "./RenderAvailable";
    export default {
        name: 'AvailableAndControl',
        props: ['product'],
        data() {
            return {
                idAvailable: null,
                maxQuantity: 999,
                valueQuantity: 1,
                minQuantity: 1
            }
        },
        methods: {
            changeIdAvailable: function (id) {
                this.idAvailable = id;
            },
        },
        watch: {
            'idAvailable': function () {
                let available = this.product.available.find((item) => item.id === this.idAvailable);
                this.maxQuantity = available.quantity;
                console.log(this.maxQuantity);
            },
            'valueQuantity': function () {
                console.log(this.valueQuantity);
                if (this.valueQuantity > this.maxQuantity) {
                    this.valueQuantity = this.maxQuantity;

                    this.$notify({
                        type: 'info',
                        title: 'Максимальное количество',
                        text: `по этим параметрам равно: ${this.maxQuantity}`
                    });
                }
            }
        },
        components: {RenderAvailable}
    }
</script>