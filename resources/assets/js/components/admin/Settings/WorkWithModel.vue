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

                <el-form-item label="Контактный телефон (1)" prop="phone1">
                    <el-input type="text" v-model="form.phone1" placeholder="Введите Контактный телефон (1)"></el-input>
                </el-form-item>

                <el-form-item label="Контактный телефон (2)" prop="phone2">
                    <el-input type="text" v-model="form.phone2" placeholder="Введите Контактный телефон (2)"></el-input>
                </el-form-item>

                <el-form-item label="Контактный E-Mail" prop="email">
                    <el-input type="text" v-model="form.email" placeholder="Введите E-Mail"></el-input>
                </el-form-item>

                <el-form-item label="Index Meta Заголовок" prop="index_m_title">
                    <el-input type="text" v-model="form.index_m_title" placeholder="Введите Index Meta Заголовок"></el-input>
                </el-form-item>

                <el-form-item label="Index Meta описание" prop="index_m_description">
                    <el-input
                            type="textarea"
                            :rows="3"
                            placeholder="Введите Index Meta описание"
                            v-model="form.index_m_description">
                    </el-input>
                </el-form-item>

                <el-form-item label="Index Meta Ключевые слова" prop="index_m_keywords">
                    <el-input type="text" v-model="form.index_m_keywords" placeholder="Введите Index Meta Ключевые слова"></el-input>
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
    import * as ApiSettings from '../../../app/admin/api/Settings';
    import * as helperRouter from '../../../app/helpers/router';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'settings-work-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;

            if (this.currentRoute.name === 'settings-update') {
                if (this.settingsStore.length) {
                    this.setDataWhenCreating(this.settingsStore);
                }
                else {
                    ApiSettings.get().then((res) => {
                        this.setDataWhenCreating(res.data.settings);
                        this.$store.commit('updateSettings', res.data.settings);
                    });
                }

                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'settings-update').meta.title;
            }

            this.setBreadcrumbElements();
        },
        data() {
            return {
                pageTitle: '',
                textBlockTitles: [],
                form: this.defaultFormData(),
                oldForm: null,
                rules: {
                    index_m_title: [
                        {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']}
                    ],
                    index_m_description: [
                        {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']}
                    ],
                    index_m_keywords: [
                        {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']}
                    ],
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success',
            }
        },
        computed: {
            settingsStore: function() {
                return this.$store.getters.settings;
            },
            changeOldForm: function (data) {
                this.oldForm = data;
            }
        },
        methods: {
            setDataWhenCreating: function(data) {
                this.form.phone1 = data.find((item) => item.slug === 'phone1').value;
                this.form.phone2 = data.find((item) => item.slug === 'phone2').value;
                this.form.email = data.find((item) => item.slug === 'email').value;
                this.form.index_m_title = data.find((item) => item.slug === 'index_m_title').value;
                this.form.index_m_keywords = data.find((item) => item.slug === 'index_m_keywords').value;
                this.form.index_m_description = data.find((item) => item.slug === 'index_m_description').value;

                this.oldForm = _.cloneDeep(this.form);
            },
            defaultFormData: function () {
                return {
                    phone1: '',
                    phone2: '',
                    email: '',
                    index_m_title: '',
                    index_m_keywords: '',
                    index_m_description: '',
                }
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;
                let settings;
                settings = [];
                settings.unshift({slug: 'phone1', value: currentData.phone1});
                settings.unshift({slug: 'phone2', value: currentData.phone2});
                settings.unshift({slug: 'email', value: currentData.email});
                settings.unshift({slug: 'index_m_title', value: currentData.index_m_title});
                settings.unshift({slug: 'index_m_keywords', value: currentData.index_m_keywords});
                settings.unshift({slug: 'index_m_description', value: currentData.index_m_description});

                this.$store.commit('updateSettings', settings);
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'settings-update') {
                            ApiSettings.update(this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.setDataWhenCreating(response.data.settings);
                                this.setDataToStore(response.data.settings);
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
            },
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts,
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'settings-update') {
                this.setDataToStore();
            }
        }
    }
</script>