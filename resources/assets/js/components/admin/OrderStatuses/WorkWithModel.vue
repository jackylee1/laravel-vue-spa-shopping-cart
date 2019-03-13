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

                <el-form-item label="Порядок сортировки" prop="sorting_order">
                    <el-input type="text" v-model="form.sorting_order" placeholder="Введите порядок сортировки"></el-input>
                </el-form-item>

                <el-form-item label="Цвет" prop="color">
                    <el-color-picker v-model="form.color"></el-color-picker>
                </el-form-item>

                <el-form-item label="По умолчанию" prop="default">
                    <el-select v-model="form.default" placeholder="">
                        <el-option
                                v-for="item in this.selectBoolean"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
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
    import * as ApiOrderStatuses from '../../../app/admin/api/OrderStatuses';
    import * as helperRouter from '../../../app/helpers/router';
    import * as helperArray from '../../../app/admin/helpers/Array';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'order-statuses-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;
            if (this.currentRoute.name === 'order-statuses-update') {
                if (this.orderStatusesStore.length) {
                    let orderStatuses = this.orderStatusesStore.find((item) => item.id === this.$route.params.id);
                    this.setDataWhenCreating(orderStatuses);
                }
                else {
                    ApiOrderStatuses.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.order_status);
                    });
                }
                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'order-statuses-update').meta.title;
            }
            else {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'order-statuses-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        computed: {
            orderStatusesStore() {
                return this.$store.getters.orderStatuses;
            },
            selectBoolean: function () {
                return this.$store.getters.selectDataBoolean;
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
                    color: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 255, min: 1, message: generatingValidationMessage('length', [255, 1]), trigger: ['blur', 'change']}
                    ],
                    default: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {
                            type: 'enum',
                            enum: [1, 0],
                            message: generatingValidationMessage('enum', [1, 2])
                        }
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
                let orderStatuses = this.orderStatusesStore;
                if (orderStatuses.length) {
                    let index = orderStatuses.findIndex((item) => item.id === this.currentRoute.params.id);
                    orderStatuses[index] = currentData;
                    this.$store.commit('updateOrderStatuses', orderStatuses);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    name: '',
                    color: '#000',
                    default: 0,
                    sorting_order: 0
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'order-statuses-list'}).href, title: helperRouter.getRouteByName(this.$router, 'order-statuses-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'order-statuses-update') {
                            ApiOrderStatuses.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });

                                this.oldForm = response.data.order_status;
                                this.setDataToStore(response.data.order_status);

                                if (this.orderStatusesStore.length) {
                                    let orderStatuses = this.orderStatusesStore;
                                    if (response.data.order_status.default) {
                                        orderStatuses = orderStatuses.map(item => {
                                            if (response.data.order_status.id !== item.id) {
                                                item.default = 0;
                                            }
                                            return item;
                                        });
                                    }
                                    orderStatuses = helperArray.sort(orderStatuses);

                                    this.$store.commit('updateOrderStatuses', orderStatuses);
                                }
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiOrderStatuses.create(this.form).then((response) => {
                                let orderStatuses = this.orderStatusesStore;
                                if (orderStatuses.length) {
                                    if (response.data.order_status.default) {
                                        orderStatuses = orderStatuses.map(item => {
                                            item.default = 0;
                                            return item;
                                        })
                                    }

                                    orderStatuses.unshift(response.data.order_status);
                                    orderStatuses = helperArray.sort(orderStatuses);

                                    this.$store.commit('updateOrderStatuses', orderStatuses);
                                }
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.$router.push({name: 'order-statuses-list'});
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
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'order-statuses-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            }
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'order-statuses-update') {
                this.setDataToStore();
            }
        }
    }
</script>