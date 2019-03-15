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

                <el-form-item label="Имя" prop="user_name">
                    <el-input type="text" v-model="form.user_name" placeholder="Введите Имя"></el-input>
                </el-form-item>

                <el-form-item label="Фамилия" prop="user_surname">
                    <el-input type="text" v-model="form.user_surname" placeholder="Введите Фамилию"></el-input>
                </el-form-item>

                <el-form-item label="Отвество" prop="user_patronymic">
                    <el-input type="text" v-model="form.user_patronymic" placeholder="Введите Отчество"></el-input>
                </el-form-item>

                <el-form-item label="E-Mail" prop="email">
                    <el-input type="text" v-model="form.email" placeholder="Введите E-Mail"></el-input>
                </el-form-item>

                <el-form-item label="Телефон" prop="phone">
                    <el-input type="text" v-model="form.phone" placeholder="Введите Телефон"></el-input>
                </el-form-item>

                <el-form-item label="Комментарий" prop="note">
                    <el-input type="textarea" v-model="form.note" placeholder="Введите комментарий"></el-input>
                </el-form-item>

                <el-form-item label="Общая цена заказа" prop="total_price">
                    <el-input type="text"
                              v-model="form.total_price"
                              :disabled="true"
                              placeholder="Общая цена заказа"></el-input>
                </el-form-item>

                <el-form-item label="Общая цена заказа (с учетом всех скидок)" prop="total_discount_price">
                    <el-input type="text"
                              v-model="form.total_discount_price"
                              :disabled="true"
                              placeholder="Общая цена заказа (с учетом всех скидок)"></el-input>
                </el-form-item>

                <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

                <el-form-item>
                    <el-button type="default" v-if="this.currentRoute.name === 'orders-update'"
                               @click="modalSelectProducts">
                        Добавить продукцию
                    </el-button>
                    <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
                </el-form-item>
            </el-form>
        </div>

        <div class="ds-block" v-if="this.form.products !== undefined && this.form.products.length">
            <h6 class="text-center">Список продукции</h6>
        </div>

        <div class="ds-block" v-if="this.form.history_statuses !== undefined && this.form.history_statuses.length">
            <h6 class="text-center">История изменения статуса заказа</h6>
            <el-table style="width: 100%"
                      :data="this.form.history_statuses">
                <el-table-column
                        fixed
                        prop="id"
                        label="ID"
                        width="70">
                </el-table-column>

                <el-table-column width="250"
                                 label="Статус">
                    <template slot-scope="props">
                        <span :style="'color: '+getOrderStatus(props.row.order_status_id).color">
                            {{getOrderStatus(props.row.order_status_id).name}}
                        </span>
                    </template>
                </el-table-column>

                <el-table-column
                        fixed="right"
                        prop="created_at"
                        label="Добавлен"
                        width="150">
                </el-table-column>
            </el-table>
        </div>

        <el-dialog width="95%" title="Управление продукцией" :visible.sync="dialogTableVisible">
            <ProductsTableSelect :model="form"
                                 v-on:selectProduct="selectProduct"/>
        </el-dialog>

        <el-dialog width="45%" v-if="operationsOnProduct !== null"
                   :title="operationsOnProduct.name + ' | Выберите опции и введите количество'"
                   :visible.sync="dialogSelectAvailableVisible">
            <el-form>
                <el-form-item label="Опция">
                    <el-select v-model="selectedAvailableId"
                               placeholder="Выберите опцию">
                        <el-option
                                v-for="item in generationSelectAvailableProduct(this.operationsOnProduct.available)"
                                :key="item.id"
                                :label="item.label"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Количество">
                    <el-input v-model="inputQuantity"
                              placeholder="Введите количество">
                    </el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary"
                           v-if="showBtnAddProductToOrder"
                           @click="addProductToOrder">Добавить к заказу</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as ApiOrders from '../../../app/admin/api/Orders';
    import * as ApiOrderStatuses from '../../../app/admin/api/OrderStatuses';
    import * as helperRouter from '../../../app/helpers/router';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { ProductsTableSelect } from '../Products';
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
            },
            orderStatuses() {
                if (this.$store.getters.orderStatuses.length === 0) {
                    ApiOrderStatuses.get().then((response) => {
                        this.$store.commit('updateOrderStatuses', response.data.order_statuses);
                    });
                }

                return this.$store.getters.orderStatuses;
            }
        },
        data() {
            return {
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                rules: {
                    user_name: [
                        {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
                    ],
                    user_surname: [
                        {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
                    ],
                    user_patronymic: [
                        {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
                    ],
                    email: [
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']},
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {type: 'email', message: generatingValidationMessage('email'), trigger: ['blur', 'change']}
                    ],
                    phone: [
                        {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']},
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                    ],
                    note: [
                        {max: 50000, message: generatingValidationMessage('length', 2500), trigger: ['blur', 'change']}
                    ],
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success',
                dialogTableVisible: false,
                operationsOnProduct: null,
                selectedAvailableId: null,
                dialogSelectAvailableVisible: false,
                inputQuantity: 0,
                showBtnAddProductToOrder: false,
            }
        },
        methods: {
            getOrderStatus: function (id){
                return this.orderStatuses.find(item => item.id === id) || {
                    name: '',
                    color: '#000'
                }
            },
            getFilter: function (id) {
                return this.$store.getters.filters.find(item => item.id === id) ||  {
                    name: null
                }
            },
            generationSelectAvailableProduct: function (available) {
                available = available.map(availableItem => {
                    let name = '';
                    availableItem.filters.forEach((filter, index) => {
                        name += this.getFilter(filter.filter_id).name;

                        if (index !== availableItem.filters.length - 1) {
                            name += ' -> ';
                        }
                    });

                    return {
                        id: availableItem.id,
                        label: name,
                    }
                });

                return available;
            },
            modalSelectProducts: function () {
                this.dialogTableVisible = true;
            },
            selectProduct: function (product) {
                this.operationsOnProduct = product;
                this.selectedAvailableId = null;
                this.inputQuantity = 0;
                this.showBtnAddProductToOrder = false;
                this.dialogSelectAvailableVisible = true;
            },
            addProductToOrder: function () {

            },
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
                    user_name: '',
                    user_surname: '',
                    user_patronymic: '',
                    phone: '',
                    email: '',
                    note: '',
                    total_price: 0,
                    total_discount_price: 0,
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
            PageElementsAlerts,
            ProductsTableSelect
        },
        watch: {
            '$route' (to, from) {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'orders-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            },
            'inputQuantity': function (value) {
                if (value > 0 && this.selectedAvailableId !== null && this.operationsOnProduct.available.length) {
                    let index = this.operationsOnProduct.available.findIndex(item => item.id === this.selectedAvailableId);
                    if (this.operationsOnProduct.available[index].quantity >= value) {
                        this.showBtnAddProductToOrder = true;
                    }
                    else {
                        this.$notify.error({
                            offset: 50,
                            title: 'Ошибка',
                            message: 'По этому параметру нет достаточного количества товара в наличии'
                        });
                        this.showBtnAddProductToOrder = false;
                    }
                }
                else {
                    this.showBtnAddProductToOrder = false;
                }
            }
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'orders-update') {
                this.setDataToStore();
            }
        }
    }
</script>