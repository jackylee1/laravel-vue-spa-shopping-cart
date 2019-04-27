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

                <el-form-item label="Заголовок" prop="title">
                    <el-input type="text" v-model="form.title" placeholder="Введите Заголовок"></el-input>
                </el-form-item>

                <el-form-item label="Добавить в заголовок" prop="text_block_title_id">
                    <el-select v-model="form.text_block_title_id" placeholder="Выберите заголовок">
                        <el-option
                                v-for="item in this.selectTextBlockTitles()"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="Тип данных" prop="type">
                    <el-select v-model="form.type" placeholder="Выберите тип">
                        <el-option
                                v-for="item in this.selectTextDataTypes"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>

                <template v-if="form.type === 0">
                    <el-form-item label="SEO URL" prop="slug">
                        <el-input type="text" v-model="form.slug" placeholder="Введите SEO URL"></el-input>
                    </el-form-item>

                    <el-form-item label="Описание" prop="description">
                        <tinymce id="description" v-model="description"
                                 :other_options="optionsTinymce"
                                 v-on:editorChange="this.changeDescription"
                                 v-on:editorInit="initTinymce"></tinymce>
                    </el-form-item>

                    <el-form-item label="Meta Заголовок" prop="m_title">
                        <el-input type="text" v-model="form.m_title" placeholder="Введите Meta Заголовок"></el-input>
                    </el-form-item>

                    <el-form-item label="Meta описание" prop="m_description">
                        <el-input
                                type="textarea"
                                :rows="3"
                                placeholder="Введите Meta описание"
                                v-model="form.m_description">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="Meta Ключевые слова" prop="m_keywords">
                        <el-input type="text" v-model="form.m_keywords" placeholder="Введите Meta Ключевые слова"></el-input>
                    </el-form-item>
                </template>

                <el-form-item v-if="form.type === 1" label="Ссылка" prop="url">
                    <el-input type="text" v-model="form.url" placeholder="Введите ссылку"></el-input>
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
    import * as ApiTextBlockTitles from '../../../app/admin/api/TextBlockTitles';
    import * as ApiTextBlockData from '../../../app/admin/api/TextBlockData';
    import * as helperRouter from '../../../app/helpers/router';
    import * as helpersArray from '../../../app/admin/helpers/Array';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    let slugify = require('slugify');

    export default {
        name: 'text-block-data-work-with-model',
        created() {
            this.currentRoute = this.$router.currentRoute;

            if (this.textBlockTitlesStore.length) {
                this.textBlockTitles = this.textBlockTitlesStore;
            }
            else {
                ApiTextBlockTitles.get().then((response) => {
                    this.textBlockTitles = response.data.text_block_titles;
                });
            }

            if (this.currentRoute.name === 'text-block-data-update') {
                if (this.textBlockData.length) {
                    let text_block_data = this.textBlockData.find((item) => item.id === this.$route.params.id);
                    this.setDataWhenCreating(text_block_data);
                }
                else {
                    ApiTextBlockData.show(this.$route.params.id).then((response) => {
                        this.setDataWhenCreating(response.data.text_block_data);
                    });
                }

                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'text-block-data-update').meta.title;
            }
            else {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'text-block-data-create').meta.title;
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
                    text_block_title_id: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                    ],
                    title: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
                    ],
                    type: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {type: 'enum', enum: [1, 0], message: generatingValidationMessage('enum', [1, 0])}
                    ],
                    sorting_order: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^\d{1,3}$/, message: 'Значение в этом поле не должно быть от 0 до 999', trigger: ['blur', 'change']}
                    ],
                    m_title: [
                        {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']}
                    ],
                    m_description: [
                        {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']}
                    ],
                    m_keywords: [
                        {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']}
                    ],
                },
                optionsTinymce: {
                    language_url: '/js/tinymce/langs/ru.js'
                },
                description: null,
                countChangesSlug: 0,
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                typeAlerts: 'success',
            }
        },
        computed: {
            textBlockTitlesStore: function() {
                return this.$store.getters.textBlockTitles;
            },
            textBlockData: function() {
                return this.$store.getters.textBlockData;
            },
            changeOldForm: function (data) {
                this.oldForm = data;
            },
            selectTextDataTypes: function () {
                return this.$store.getters.selectDataTextBlockDataTypes;
            }
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
            selectTextBlockTitles: function () {
                let select = [];
                this.textBlockTitles.forEach(function (item) {
                    select.push({
                        value: item.id,
                        label: item.title
                    });
                });

                return select;
            },
            setDataWhenCreating: function(data) {
                this.form = data;
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;

                let text_block_data = this.textBlockData;
                if (text_block_data.length) {
                    let index = text_block_data.findIndex((item) => item.id === this.currentRoute.params.id);
                    text_block_data[index] = currentData;
                    text_block_data = helpersArray.sort(text_block_data);

                    this.$store.commit('updateTextBlockData', text_block_data);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    text_block_title_id: null,
                    title: '',
                    type: 0,
                    url: '',
                    slug: '',
                    description: '',
                    sorting_order: 0,
                    m_title: '',
                    m_keywords: '',
                    m_description: '',
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'text-block-data-list'}).href, title: helperRouter.getRouteByName(this.$router, 'text-block-data-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'text-block-data-update') {
                            ApiTextBlockData.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.setDataWhenCreating(response.data.text_block_data);
                                this.setDataToStore(response.data.text_block_data);
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiTextBlockData.create(this.form).then((response) => {
                                let text_block_data = this.textBlockData;
                                if (text_block_data.length) {
                                    text_block_data.unshift(response.data.text_block_data);
                                    text_block_data = helpersArray.sort(text_block_data);

                                    this.$store.commit('updateTextBlockData', text_block_data);
                                }
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.$router.push({name: 'text-block-data-list'});
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
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'text-block-data-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            },
            'form.type': function (val) {
                if (val === 0) {
                    if (this.rules['description'] === undefined) {
                        this.rules['description'] = [
                            {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']},
                        ];
                    }
                }
                else {
                    if (this.rules['url'] === undefined) {
                        this.rules['url'] = [
                            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                            {type: 'url', message: generatingValidationMessage('url'), trigger: ['blur', 'change']},
                            {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
                        ];
                    }
                }
            },
            'form.title': function (val) {
                if (this.countChangesSlug !== 0 && this.form.type === 0) {
                    if (val !== undefined) {
                        this.form.slug = slugify(val, {
                            replacement: '-',
                            remove: null,
                            lower: true
                        })
                    }
                }
                this.countChangesSlug += 1;
            },
            'form.slug': function (val) {
                if (val !== undefined) {
                    this.form.slug = slugify(val, {
                        replacement: '-',
                        remove: null,
                        lower: true
                    })
                }
            }
        },
        beforeDestroy() {
            if (this.currentRoute.name === 'text-block-data-update') {
                this.setDataToStore();
            }
        }
    }
</script>