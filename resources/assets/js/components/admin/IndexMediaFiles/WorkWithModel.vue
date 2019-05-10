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

                <el-form-item label="Ссылка на Youtube видео" prop="video">
                    <el-input type="text" v-model="form.video" placeholder="Введите Ссылка на Youtube видео"></el-input>
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
    import * as ApiIndexMediaFiles from '../../../app/admin/api/IndexMediaFiles';
    import * as helperRouter from '../../../app/helpers/router';
    import * as helpersArray from '../../../app/admin/helpers/Array';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'index-media-files-work-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;

            if (this.currentRoute.name === 'index-media-files-update') {
                if (this.indexMediaFiles.length) {
                    let indexMediaFiles = this.indexMediaFiles.find((item) => item.id === this.$route.params.id);
                    this.setDataWhenCreating(indexMediaFiles);
                }
                else {
                    ApiIndexMediaFiles.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.index_media_file);
                    });
                }

                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'index-media-files-update').meta.title;
            }
            else {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'index-media-files-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        data() {
            return {
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                rules: {
                    video: [
                        {type: 'url', required: true, message: generatingValidationMessage('url'), trigger: ['blur', 'change']},
                        {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
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
                typeAlerts: 'success',
            }
        },
        computed: {
            indexMediaFiles: function() {
                return this.$store.getters.indexMediaFiles;
            },
            changeOldForm: function (data) {
                this.oldForm = data;
            },
        },
        methods: {
            setDataWhenCreating: function(data) {
                this.form = data;
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;

                let indexMediaFiles = this.indexMediaFiles;
                if (indexMediaFiles.length) {
                    let index = indexMediaFiles.findIndex((item) => item.id === this.currentRoute.params.id);
                    indexMediaFiles[index] = currentData;
                    indexMediaFiles = helpersArray.sort(indexMediaFiles);

                    this.$store.commit('updateIndexMediaFiles', indexMediaFiles);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    video: '',
                    sorting_order: 0,
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'index-media-files-list'}).href, title: helperRouter.getRouteByName(this.$router, 'index-media-files-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'index-media-files-update') {
                            ApiIndexMediaFiles.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.setDataWhenCreating(response.data.index_media_file);
                                this.setDataToStore(response.data.index_media_file);
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiIndexMediaFiles.create(this.form).then((response) => {
                                let indexMediaFiles = this.indexMediaFiles;
                                if (indexMediaFiles.length) {
                                    indexMediaFiles.unshift(response.data.index_media_file);
                                    indexMediaFiles = helpersArray.sort(indexMediaFiles);

                                    this.$store.commit('updateIndexMediaFiles', indexMediaFiles);
                                }
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.$router.push({name: 'index-media-files-list'});
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
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'index-media-files-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            },
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'index-media-files-update') {
                this.setDataToStore();
            }
        }
    }
</script>