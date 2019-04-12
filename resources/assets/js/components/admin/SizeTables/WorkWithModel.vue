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

                <el-form-item label="Наименование" prop="title">
                    <el-input type="text" v-model="form.title" placeholder="Введите Наименование"></el-input>
                </el-form-item>

                <el-form-item label="Описание" prop="description">
                    <tinymce id="description" v-model="description"
                             :other_options="optionsTinymce"
                             v-on:editorChange="this.changeDescription"
                             v-on:editorInit="initTinymce"></tinymce>
                </el-form-item>

                <el-form-item label="Порядок сортировки" prop="sorting_order">
                    <el-input type="text" v-model="form.sorting_order" placeholder="Введите Порядок сортировки"></el-input>
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
    import * as ApiSizeTables from '../../../app/admin/api/SizeTables';
    import * as helperRouter from '../../../app/helpers/router';
    import * as helpersArray from '../../../app/admin/helpers/Array';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'size-tables-work-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;

            if (this.currentRoute.name === 'size-tables-update') {
                if (this.sizeTables.length) {
                    this.setDataWhenCreating(this.sizeTables.find((item) => item.id === this.$route.params.id));
                }
                else {
                    ApiSizeTables.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.size_table);
                    });
                }

                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'size-tables-update').meta.title;
            }
            else {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'size-tables-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        data() {
            return {
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                rules: {
                    title: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
                    ],
                    sorting_order: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^\d{1,3}$/, message: 'Значение в этом поле не должно быть от 0 до 999', trigger: ['blur', 'change']}
                    ],
                    description: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']},
                    ]
                },
                optionsTinymce: {
                    language_url: '/js/tinymce/langs/ru.js'
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success',
                description: null
            }
        },
        computed: {
            sizeTables: function() {
                return this.$store.getters.sizeTables;
            },
            changeOldForm: function (data) {
                this.oldForm = data;
            },
        },
        methods: {
            changeDescription: function() {
                this.form.description = (this.description.length)
                    ? this.description.replace(/(\")[\.\/]{2,}/, '$1/')
                    : '';
            },
            initTinymce: function () {
                this.description = this.form.description;
            },
            setDataWhenCreating: function(data) {
                this.form = data;
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;

                let sizeTables = this.sizeTables;
                if (sizeTables.length) {
                    let index = sizeTables.findIndex((item) => item.id === this.currentRoute.params.id);
                    sizeTables[index] = currentData;
                    sizeTables = helpersArray.sort(sizeTables);

                    this.$store.commit('updateTextBlockTitles', sizeTables);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    title: '',
                    sorting_order: 0,
                    description: '<table class="table size_table_title"> <thead class="thead-light"> <tr class="table_head"> <th scope="col">Размер</th> <th scope="col">Грудь</th> <th scope="col">Талия</th> </tr></thead> <tbody> <tr> <th scope="row">S</th> <td>90-94</td><td>78-82</td></tr><tr> <th scope="row">M</th> <td>94-98</td><td>82-86</td></tr><tr> <th scope="row">L</th> <td>98-102</td><td>86-90</td></tr><tr> <th scope="row">XL</th> <td>102-106</td><td>92-96</td></tr><tr> <th scope="row">XXL</th> <td>106-110</td><td>96-100</td></tr></tbody> </table>'
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'size-tables-list'}).href, title: helperRouter.getRouteByName(this.$router, 'size-tables-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'size-tables-update') {
                            ApiSizeTables.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.setDataWhenCreating(response.data.size_table);
                                this.setDataToStore(response.data.size_table);
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiSizeTables.create(this.form).then((response) => {
                                let sizeTables = this.sizeTables;
                                if (sizeTables.length) {
                                    sizeTables.unshift(response.data.size_table);
                                    sizeTables = helpersArray.sort(sizeTables);

                                    this.$store.commit('updateSizeTables', sizeTables);
                                }
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.$router.push({name: 'size-tables-list'});
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
        watch: {
            '$route' (to, from) {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'size-tables-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            },
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'size-tables-update') {
                this.setDataToStore();
            }
        }
    }
</script>