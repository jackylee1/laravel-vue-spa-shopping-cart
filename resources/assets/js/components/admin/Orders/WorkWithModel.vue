<template>
    <div>
        <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

        <div class="ds-block" v-on:click="alerts = []">
            <el-form label-position="top" class="ds-source"
                     ref="formWorkWithModel"
                     :rules="rules"
                     @keydown.enter="onSubmit"
                     :model="form"
                     label-width="120px">

                <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

                <el-form-item>
                    <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
    import * as ApiOrders from '../../../app/admin/api/Orders';
    import * as helperRouter from '../../../app/helpers/router';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'orders-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;
            if (this.currentRoute.name === 'orders-update') {
                if (this.ordersInStore.data !== undefined && this.ordersInStore.data.length) {
                    let order = this.ordersInStore.data.find((item) => item.id === this.$route.params.id);
                    this.setDataWhenCreating(order);
                }
                else {
                    ApiOrders.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.order);
                    });
                }
                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'orders-update').meta.title;
            }
            else {
                ApiOrders.create({}).then((response) => {
                    if (response.data.status === 'success') {
                        this.form = response.data.order;

                        let orders = this.ordersInStore;
                        if (orders.data !== undefined && orders.data.length) {
                            orders.data.unshift(response.data.order);

                            this.$store.commit('updateOrders', orders);
                        }
                        this.$notify.success({
                            offset: 50,
                            title: 'Запрос успешно выполнен',
                            message: response.data.message
                        });

                        this.$router.push({name: 'orders-list', query: {update_model: this.form.id}});
                    }
                }).catch((error) => {});
            }

            this.setBreadcrumbElements();
        },
        computed: {
            ordersInStore() {
                return this.$store.getters.orders;
            }
        },
        data() {
            return {
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                rules: {
                    name: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 255, min: 1, message: generatingValidationMessage('length', [255, 1]), trigger: ['blur', 'change']}
                    ],
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success',
            }
        },
        methods: {
            setDataWhenCreating: function(data) {
                this.form = data;
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;
                let orders = this.ordersInStore;
                if (orders.data) {
                    let index = orders.data.findIndex((item) => item.id === this.currentRoute.params.id);
                    orders.data[index] = currentData;

                    this.$store.commit('updateOrders', orders);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'orders-list'}).href, title: helperRouter.getRouteByName(this.$router, 'orders-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'orders-update') {
                            ApiOrders.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });

                                this.oldForm = response.data.order;
                                this.setDataToStore(response.data.order);
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                    } else {
                        this.$notify.error({
                            offset: 50,
                            title: 'Ошибка',
                            message: 'При проверке данных произошла ошибка. Проверте форму ввода данных.'
                        });
                        return false;
                    }
                });
            }
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts
        },
        watch: {
            '$route' (to, from) {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'orders-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            }
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'orders-update') {
                this.setDataToStore();
            }
        }
    }
</script>