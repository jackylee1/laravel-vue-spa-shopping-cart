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
                <el-form-item label="Наименование" prop="name">
                    <el-input type="text" v-model="form.name" placeholder="Введите наименование"></el-input>
                </el-form-item>

                <el-form-item label="Порядок сорт." prop="sorting_order">
                    <el-input type="text" v-model="form.sorting_order" placeholder="Введите Порядок сорт."></el-input>
                </el-form-item>

                <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

                <el-form-item>
                    <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
    import * as ApiOrderPaymentMethods from '../../../app/admin/api/OrderPaymentMethods';
    import * as helperRouter from '../../../app/helpers/router';
    import * as helperArray from '../../../app/admin/helpers/Array';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'order-payment-methods-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;
            if (this.currentRoute.name === 'order-payment-methods-update') {
                if (this.orderPaymentMethodsStore.length) {
                    let orderPaymentMethod = this.orderPaymentMethodsStore.find((item) => item.id === this.$route.params.id);
                    this.setDataWhenCreating(orderPaymentMethod);
                }
                else {
                    ApiOrderPaymentMethods.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.order_payment_method);
                    });
                }
                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'order-payment-methods-update').meta.title;
            }
            else {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'order-payment-methods-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        computed: {
            orderPaymentMethodsStore() {
                return this.$store.getters.orderPaymentMethods;
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
                        {max: 191, min: 1, message: generatingValidationMessage('length', [255, 1]), trigger: ['blur', 'change']}
                    ],
                    sorting_order: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^\d{1,3}$/, message: 'Значение в этом поле не должно быть от 0 до 999', trigger: ['blur', 'change']}
                    ],
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success'
            }
        },
        methods: {
            setDataWhenCreating: function(orderStatus) {
                this.form = orderStatus;
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;
                let orderPaymentMethods = this.orderPaymentMethodsStore;
                if (orderPaymentMethods.length) {
                    let index = orderPaymentMethods.findIndex((item) => item.id === this.currentRoute.params.id);
                    orderPaymentMethods[index] = currentData;
                    orderPaymentMethods = helperArray.sort(orderPaymentMethods);

                    this.$store.commit('updateOrderPaymentMethods', orderPaymentMethods);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    name: '',
                    sorting_order: 0
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'order-payment-methods-list'}).href, title: helperRouter.getRouteByName(this.$router, 'order-payment-methods-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'order-payment-methods-update') {
                            ApiOrderPaymentMethods.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });

                                this.oldForm = response.data.order_payment_method;
                                this.setDataToStore(response.data.order_payment_method);
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiOrderPaymentMethods.create(this.form).then((response) => {
                                let orderPaymentMethods = this.orderPaymentMethodsStore;
                                if (orderPaymentMethods.length) {
                                    orderPaymentMethods.unshift(response.data.order_payment_method);
                                    orderPaymentMethods = helperArray.sort(orderPaymentMethods);

                                    this.$store.commit('updateOrderPaymentMethods', orderPaymentMethods);
                                }

                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });

                                this.$router.push({name: 'order-payment-methods-list'});
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
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'order-payment-methods-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            }
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'order-payment-methods-update') {
                this.setDataToStore();
            }
        }
    }
</script>