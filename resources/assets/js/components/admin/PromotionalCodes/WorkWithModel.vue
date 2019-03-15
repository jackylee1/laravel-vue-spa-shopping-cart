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
                <el-form-item label="Промокод" prop="code">
                    <el-input :disabled="true" type="text" v-model="form.code" placeholder="Введите промокод"></el-input>
                </el-form-item>

                <el-form-item label="Процент скидки" prop="discount">
                    <el-input type="text" v-model="form.discount" placeholder="Введите процент скидки"></el-input>
                </el-form-item>

                <el-form-item label="Статус" prop="status">
                    <el-select v-model="form.status" placeholder="Выберите статус">
                        <el-option
                                v-for="item in this.selectListPromotionalCodeStatuses"
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
    import * as ApiPromotionalCodes from '../../../app/admin/api/PromotionalCodes';
    import * as helperRouter from '../../../app/helpers/router';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import {generatingValidationMessage} from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'users-work-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;
            if (this.currentRoute.name === 'promotional-codes-update') {
                if (this.promotionalCodes.data !== undefined && this.promotionalCodes.data.length) {
                    let promotionalCode = this.promotionalCodes.data.find((code) => code.id === this.$route.params.id);
                    this.setDataWhenCreating(promotionalCode);
                }
                else {
                    ApiPromotionalCodes.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.promotional_code);
                    });
                }
                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'promotional-codes-update').meta.title;
            }
            else {
                ApiPromotionalCodes.getFree().then((response) => {
                    this.form.code = response.data.promotional_code;
                });
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'promotional-codes-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        data() {
            return {
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                rules: {
                    code: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 255, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    status: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {
                            type: 'enum',
                            enum: [1, 0],
                            message: generatingValidationMessage('enum', [1, 2])
                        }
                    ],
                    discount: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^(?:100|[1-9]?[0-9])$/, message: 'Значение в этом поле должно быть от 0 до 100', trigger: ['blur', 'change']}
                    ],
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success'
            }
        },
        computed: {
            promotionalCodes() {
                return this.$store.getters.promotionalCodes;
            },
            selectListPromotionalCodeStatuses: function () {
                return this.$store.getters.selectDataListPromotionalCodeStatuses;
            }
        },
        methods: {
            setDataWhenCreating: function(promotionalCode) {
                this.form = promotionalCode;
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;
                let promotionalCodes = this.promotionalCodes;
                if (promotionalCodes.data) {
                    let index = promotionalCodes.data.findIndex((code) => code.id === this.currentRoute.params.id);
                    promotionalCodes.data[index] = currentData;
                    this.$store.commit('updatePromotionalCodes', promotionalCodes);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    code: '',
                    status: 1,
                    discount: 0,
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'promotional-codes-list'}).href, title: helperRouter.getRouteByName(this.$router, 'promotional-codes-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'promotional-codes-update') {
                            let self = this;
                            ApiPromotionalCodes.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });

                                self.oldForm = response.data.promotional_code;
                                self.setDataToStore(response.data.promotional_code);
                            }).catch((error) => {
                                self.alerts = error.response.data.errors;
                                self.typeAlerts = 'error';
                            });
                        }
                        else {
                            let self = this;
                            ApiPromotionalCodes.create(self.form).then((response) => {
                                let promotionalCodes = self.promotionalCodes;
                                if (promotionalCodes.data !== undefined && promotionalCodes.data.length) {
                                    promotionalCodes.data.unshift(response.data.promotional_code);
                                    self.$store.commit('updatePromotionalCodes', promotionalCodes);
                                }
                                self.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                self.$router.push({name: 'promotional-codes-list'});
                            }).catch((error) => {
                                self.alerts = error.response.data.errors;
                                self.typeAlerts = 'error';
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
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'promotional-codes-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            }
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'promotional-codes-update') {
                this.setDataToStore();
            }
        }
    }
</script>