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
                <el-form-item label="Текущее изображение" v-if="form.image_preview !== null">
                    <img width="83" height="auto" :src="'/app/public/images/social_network/'+form.image_preview">
                </el-form-item>

                <el-form-item label="Изображение" prop="image">
                    <el-upload
                            name="image"
                            :multiple="false"
                            :show-file-list="false"
                            :on-change="changeDataInImageField"
                            :action="'windows.location.href'"
                            :auto-upload="false">
                        <el-button slot="trigger" size="small" type="primary">Выбрать изображение</el-button>
                        <el-button style="margin-left: 10px;" size="small" type="danger" @click="resetImage">Очистить</el-button>
                        <div class="el-upload__tip" slot="tip">jpg/png/jpeg/gif/ изображения размером не более 2048kb</div>
                        <div v-if="fileName.length">Выбранное изображение: {{fileName}}</div>
                    </el-upload>
                </el-form-item>

                <el-form-item label="Ссылка" prop="url">
                    <el-input type="text" v-model="form.url" placeholder="Введите Ссылку"></el-input>
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
    import * as ApiLinkToSocialNetworks from '../../../app/admin/api/LinkToSocialNetworks';
    import * as helperRouter from '../../../app/helpers/router';
    import * as helpersArray from '../../../app/admin/helpers/Array';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    export default {
        name: 'link-to-social-networks-work-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;

            if (this.currentRoute.name === 'link-to-social-networks-update') {
                if (this.linkToSocialNetworks !== undefined && this.linkToSocialNetworks.length) {
                    let link = this.linkToSocialNetworks.find((item) => item.id === this.$route.params.id);
                    this.setDataWhenCreating(link);
                }
                else {
                    ApiLinkToSocialNetworks.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.link_to_social_network);
                    });
                }

                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'link-to-social-networks-update').meta.title;
            }
            else {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'link-to-social-networks-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        data() {
            return {
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                fileName: '',
                rules: {
                    image: [
                        {
                            required: (this.$router.currentRoute.name !== 'link-to-social-networks-update'),
                            message: generatingValidationMessage('required'), trigger: ['blur', 'change']
                        },
                    ],
                    url: [
                        {type: 'url', message: generatingValidationMessage('url'), trigger: ['blur', 'change']},
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
            linkToSocialNetworks: function() {
                return this.$store.getters.linkToSocialNetworks;
            },
        },
        methods: {
            changeOldForm: function (data) {
                this.oldForm = data;
            },
            resetImage: function() {
                this.form.image = null;
                this.fileName = '';
            },
            changeDataInImageField: function(file) {
                this.fileName = file.name;
                this.form.image = file.raw;
            },
            setDataWhenCreating: function(data) {
                this.form = data;
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;

                let links = this.linkToSocialNetworks;
                if (links.length) {
                    let index = links.findIndex((item) => item.id === this.currentRoute.params.id);
                    links[index] = currentData;
                    links = helpersArray.sort(links);
                    this.$store.commit('updateLinkToSocialNetworks', links);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    url: '',
                    sorting_order: 0,
                    image: {},
                    image_preview: null,
                    image_origin: null,
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'link-to-social-networks-list'}).href, title: helperRouter.getRouteByName(this.$router, 'link-to-social-networks-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            getFromData: function () {
                let formData = new FormData();
                Object.keys(this.form).forEach(key => {
                    if (this.form[key] !== null) {
                        formData.append(key, this.form[key]);
                    }
                });

                return formData;
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'link-to-social-networks-update') {
                            let self = this;
                            ApiLinkToSocialNetworks.update(this.$route.params.id, this.getFromData()).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.setDataWhenCreating(response.data.link_to_social_network);
                                self.setDataToStore(response.data.link_to_social_network);
                            }).catch((error) => {
                                self.alerts = error.response.data.errors;
                                self.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiLinkToSocialNetworks.create(this.getFromData()).then((response) => {
                                let links = this.linkToSocialNetworks;
                                if (links.length) {
                                    links.unshift(response.data.link_to_social_network);
                                    links = helpersArray.sort(links);
                                    this.$store.commit('updateLinkToSocialNetwork', links);
                                }
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.$router.push({name: 'link-to-social-networks-list'});
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
                this.image = null;
                this.fileName = '';
            },
        },
        components: {
            PageElementsBreadcrumb,
            PageElementsAlerts,
        },
        watch: {
            '$route' (to, from) {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'link-to-social-networks-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            },
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'link-to-social-networks-update') {
                this.setDataToStore();
            }
        }
    }
</script>

<style scoped>
    .upload-images {
        padding: 25px;
        width: 100%;
    }
    .el-upload__tip {
        padding-top: 5px;
    }
</style>