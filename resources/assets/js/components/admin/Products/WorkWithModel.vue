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
                <el-form-item label="Артикул" prop="article">
                    <el-input type="text" v-model="form.article" placeholder="Введите Артикул"></el-input>
                </el-form-item>

                <el-form-item label="Наименование" prop="name">
                    <el-input type="text" v-model="form.name" placeholder="Введите Наименование"></el-input>
                </el-form-item>

                <el-form-item label="SEO URL" prop="slug">
                    <el-input type="text" v-model="form.slug" placeholder="Введите SEO URL"></el-input>
                </el-form-item>

                <el-form-item label="Описание" prop="description">
                    <tinymce id="description" v-model="description"
                             :other_options="optionsTinymce"
                             v-on:editorChange="this.changeDescription"
                             v-on:editorInit="initTinymce"></tinymce>
                </el-form-item>

                <el-form-item label="Краткое описание" prop="preview_description">
                    <el-input
                            type="textarea"
                            :rows="3"
                            placeholder="Введите Краткое описание"
                            v-model="form.preview_description">
                    </el-input>
                </el-form-item>

                <el-form-item label="Цена" prop="price">
                    <el-input type="text" v-model="form.price" placeholder="Введите Цену"></el-input>
                </el-form-item>

                <el-form-item label="Скидка (%)" prop="discount">
                    <el-input type="text" v-model="form.discount" placeholder="Скидка (%)"></el-input>
                </el-form-item>

                <el-form-item label="Отображать на сайте?" prop="status">
                    <el-select v-model="form.status" placeholder="Выберите статус продукта">
                        <el-option
                                v-for="item in this.productStatuses"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="Дата включения" prop="date_inclusion">
                    <el-date-picker
                            v-model="form.date_inclusion"
                            type="date"
                            placeholder="Выберите дату включения">
                    </el-date-picker>
                </el-form-item>

                <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

                <el-form-item>
                    <el-button v-if="currentRoute.name === 'products-update'"
                               type="default"
                               @click="dialogWorkWithFilter">Управление фильтрами</el-button>
                    <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
                </el-form-item>
            </el-form>
        </div>

        <div class="ds-block" v-if="currentRoute.name === 'products-update'">
            <h4 class="text-center">Добавлен в</h4>
            <el-table
                    :data="form.filters"
                    style="width: 100%">
                <el-table-column
                        fixed
                        label="Тип">
                    <template slot-scope="props">
                        {{ getType(props.row.type_id).name }}
                    </template>
                </el-table-column>
                <el-table-column
                        fixed
                        label="Категория">
                    <template slot-scope="props">
                        <template v-for="(category, index) in props.row.categories">
                            {{ getCategory(props.row.type_id, category.category_id).name }}
                            <template v-if="index !== props.row.categories.length - 1">
                                <i class="ai-arrow-right"></i>
                            </template>
                        </template>
                    </template>
                </el-table-column>
                <el-table-column
                        fixed
                        label="Фильтр">
                    <template slot-scope="props">
                        {{ getFilter(props.row.filter_id).name }}
                    </template>
                </el-table-column>
                <el-table-column
                        fixed="right"
                        label="Управление"
                        min-width="100">
                    <template slot-scope="props">
                        <el-button
                                size="mini"
                                type="danger"
                                @click.native.prevent="removeFilterProduct(props.$index, form.filters)">
                            <i class="el-icon-delete"></i>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <div class="ds-block" v-if="currentRoute.name === 'products-update'">
            <h4 class="text-center">Список изображений</h4>

            <PageElementsAlerts :alerts="alertsUploadImages" :type="typeAlerts"/>

            <el-upload
                    :multiple="true"
                    drag
                    name="image"
                    class="upload-images"
                    :action="actionUploadImages"
                    :http-request="uploadImages"
                    :on-preview="handlePreviewImages"
                    :on-remove="handleRemoveImages"
                    :file-list="imagesList"
                    :on-change="onChangeImages"
                    list-type="picture">
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">Переместите в эту область изображение или <em>кликните чтобы загрузить</em></div>
                <div slot="tip" class="el-upload__tip">jpg/png/jpeg/gif/ изображения размером не более 2048kb</div>
            </el-upload>
        </div>

        <el-dialog width="40%" :title="titleDialogWorkWith" :visible.sync="visibleDialogWorkWithFilters">
            <el-form ref="formWorkWithFilters">
                <el-form-item label="Выберите тип продукции">
                    <el-select v-model="selectedType"
                               @change="changeSelectType"
                               placeholder="Выберите тип продукции">
                        <el-option
                                v-for="item in types"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item v-if="selectedType !== null" label="Выберите категорию">
                    <el-cascader
                            :options="this.getTreeCategories()"
                            :props="categoryProps"
                            v-model="selectedCategory"
                            @change="changeSelectCategory">
                    </el-cascader>
                </el-form-item>
                <el-form-item v-if="selectFilters.length" label="Выберите фильтр">
                    <el-select v-model="selectedFilter" placeholder="Выберите фильтр">
                        <el-option
                                v-for="item in selectFilters"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <PageElementsAlerts :alerts="modalAlerts" :type="modalTypeAlerts"/>
            <span slot="footer" class="dialog-footer">
                <el-button @click="visibleDialogWorkWithFilters = false">Отмена</el-button>
                <el-button type="primary"
                           @click="addFilterToProduct">Сохранить</el-button>
            </span>
        </el-dialog>

        <el-dialog width="40%" :title="titleDialogWorkWith" :visible.sync="visibleDialogWorkWithImage">
            <el-form ref="formWorkWithImage"
                     :rules="rulesImage"
                     :model="workWithImage">
                <img height="50%" width="auto" v-bind:src="this.getFullPathImage(this.productImageOnModal)" class="ds-image">
                <el-button-group style="text-align:center;margin-top:15px;margin-bottom:15px;">
                    <el-button :disabled="this.currentProductImageOnModal === 'origin'" type="primary" @click="changeProductImageOnModal('origin')">Оригинал</el-button>
                    <el-button :disabled="this.currentProductImageOnModal === 'preview'" type="primary" @click="changeProductImageOnModal('preview')">Превью</el-button>
                </el-button-group>
                <el-form-item label="Порядок сортировки" prop="sorting_order">
                    <el-input v-model="workWithImage.sorting_order"></el-input>
                </el-form-item>
                <el-form-item label="Статус изображения" prop="main_status">
                    <el-select v-model="workWithImage.main_status" placeholder="Статус изображения">
                        <el-option
                                v-for="item in mainStatuses"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <PageElementsAlerts :alerts="modalAlerts" :type="modalTypeAlerts"/>
            <span slot="footer" class="dialog-footer">
                <el-button @click="visibleDialogWorkWithImage = false">Отмена</el-button>
                <el-button @click="this.openModalCropImage">Редактировать превью</el-button>
                <el-button type="primary" @click="clickWorkWithImage">Сохранить</el-button>
            </span>
        </el-dialog>

        <el-dialog  style="text-align:center" width="35%" :title="titleDialogWorkWith" :visible.sync="visibleDialogWorkWithCropImage">
            <croppa
                :width="350"
                :height="350"
                :show-remove-button="false"
                :disable-drag-and-drop="true"
                :disable-click-to-choose="true"
                :zoom-speed="6"
                :prevent-white-space="true"
                :initial-image="this.initialImageCroppa"
                initial-position="center"
                v-model="imageCroppa"></croppa>
            <PageElementsAlerts :alerts="modalAlerts" :type="modalTypeAlerts"/>
            <span slot="footer" class="dialog-footer">
                <el-button @click="visibleDialogWorkWithCropImage = false">Отмена</el-button>
                <el-button type="primary" @click="clickWorkWithCropImage">Сохранить</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import * as ApiProducts from '../../../app/admin/api/Products';
    import * as ApiProductImages from '../../../app/admin/api/ProductImages';
    import * as ApiFilters from '../../../app/admin/api/Filters';
    import * as ApiTypes from '../../../app/admin/api/Types';
    import * as helperRouter from '../../../app/helpers/router';
    import * as helperArray from '../../../app/admin/helpers/Array';

    import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
    import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

    let arrayToTree = require('array-to-tree');
    let slugify = require('slugify');

    export default {
        name: 'products-work-with-model',
         created() {
            if (this.typesStore.length) {
                this.types = this.typesStore;
            }
            else {
                this.setTypes();
            }

             if (this.filtersStore.length) {
                 this.filters = this.filtersStore;
             }
             else {
                 this.setFilters();
             }

            this.currentRoute = this.$router.currentRoute;
            if (this.currentRoute.name === 'products-update') {
                if (this.products.data !== undefined && this.products.data.length) {
                    let product = this.products.data.find((item) => item.id === this.$route.params.id);
                    this.setDataWhenCreating(product);
                }
                else {
                    ApiProducts.show(this.$route.params.id).then((response) => {
                        let product = response.data.product;
                        this.setDataWhenCreating(product);
                    });
                }

                this.submitName = 'Обновить';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'products-update').meta.title;
            }
            else {
                this.submitName = 'Создать';
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'products-create').meta.title;
            }

            this.setBreadcrumbElements();
        },
        computed: {
            actionUploadImages() {
                return '/api/admin/products/upload_images';
            },
            productStatuses: function () {
                return this.$store.getters.selectDataListProductStatuses;
            },
            products: function() {
                return this.$store.getters.products;
            },
            filtersStore: function () {
                return this.$store.getters.filters;
            },
            typesStore: function () {
                return this.$store.getters.types;
            },
        },
        data() {
            return {
                productImageOnModal: '',
                currentProductImageOnModal: 'origin',
                imageCroppa: null,
                initialImageCroppa: '',
                visibleDialogWorkWithCropImage: false,
                titleDialogWorkWith: '',
                visibleDialogWorkWithImage: false,
                workWithImage: {
                    sorting_order: 0,
                    main_status: 0
                },
                modalAlerts: [],
                modalTypeAlerts: 'success',
                imagesList: [],
                description: null,
                pageTitle: '',
                form: this.defaultFormData(),
                oldForm: null,
                countChangesSlug: 0,
                rulesImage: {
                    sorting_order: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^\d+$/, message: generatingValidationMessage('integer'), trigger: ['blur', 'change']}
                    ],
                },
                rules: {
                    name: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 255, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    slug: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 255, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    article: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 255, min: 1, message: generatingValidationMessage('length', [255, 1]), trigger: ['blur', 'change']}
                    ],
                    description: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 10000, min: 3, message: generatingValidationMessage('length', [10000, 3]), trigger: ['blur', 'change']}
                    ],
                    preview_description: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {max: 2000, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
                    ],
                    price: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {pattern: /^\d+(\.\d{1,2})?$/, message: 'Недопустимое значение. Значение в этом поле должно быть числом с плавающей точкой. (150.99, 1500.5, и тп)', trigger: ['blur', 'change']}
                    ],
                    discount: [
                        {pattern: /^[1-9][0-9]?$|^100$/, message: 'Значение в этом поле должно быть от 1 до 100', trigger: ['blur', 'change']}
                    ],
                    status: [
                        {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
                        {
                            type: 'enum',
                            enum: [1, 0],
                            message: generatingValidationMessage('enum', [1, 2])
                        }
                    ],
                    date: [
                        {type: 'date', message: generatingValidationMessage('date'), trigger: ['blur', 'change']}
                    ],
                },
                currentRoute: null,
                breadcrumbElements: [],
                submitName: null,
                alerts: [],
                alertsUploadImages: [],
                typeAlerts: 'success',
                optionsTinymce: {
                    language_url: '/js/tinymce/langs/ru.js'
                },
                mainStatuses: [
                    {value: 0, label: 'Изображение'},
                    {value: 1, label: 'Главное изображение'}
                ],
                filters: [],
                treeTypes: [],
                types: [],
                visibleDialogWorkWithFilters: false,
                selectFilters: [],
                selectedType: null,
                selectedFilter: null,
                selectedCategory: [],
                selectedTypeObject: {},
                categoryProps: {
                    value: 'id',
                    label: 'name',
                    children: 'children'
                },
            }
        },
        methods: {
            removeFilterProduct: function (index, filters) {
                let filter = filters[index];
                ApiProducts.removeFilterToProduct({
                    'id': filter.id,
                }).then((response) => {
                    if(response.data.status === 'success') {
                        let index = this.form.filters.findIndex(item => item.id === filter.id);
                        this.form.filters.splice(index, 1);
                        this.oldForm = this.form;
                        this.setDataToStore();
                        this.$notify.success({
                            offset: 50,
                            title: 'Запрос выполнен',
                            message: response.data.message
                        });
                    }
                }).catch((error) => {
                    this.alerts = error.response.data.errors;
                    this.typeAlerts = 'error';
                });
            },
            resetFormAddFilter: function () {
                this.selectedType = null;
                this.selectedFilter = null;
                this.selectedCategory = this.selectFilters = [];
            },
            addFilterToProduct: function () {
                ApiProducts.addFilterToProduct({
                    'product_id': this.form.id,
                    'type_id': this.selectedType,
                    'category_id': this.lastSelectedCategory(),
                    'filter_id': this.selectedFilter,
                    'categories': this.selectedCategory,
                }).then((response) => {
                    if(response.data.status === 'success') {
                        this.form.filters.unshift(response.data.filter);
                        this.oldForm = this.form;
                        this.setDataToStore();

                        this.resetFormAddFilter();
                        this.visibleDialogWorkWithFilters = false;

                        this.$notify.success({
                            offset: 50,
                            title: 'Запрос выполнен',
                            message: response.data.message
                        });
                    }
                }).catch((error) => {
                    this.modalAlerts = error.response.data.errors;
                    this.modalTypeAlerts = 'error';
                });
            },
            lastSelectedCategory: function () {
                return (this.selectedCategory.length) ? this.selectedCategory[this.selectedCategory.length - 1] : null;
            },
            changeSelectType: function () {
                this.selectedCategory = this.selectFilters = this.modalAlerts = [];
                this.selectedTypeObject = this.types.find((item) => item.id === this.selectedType);
                if (this.selectedTypeObject.filters.length) {
                    this.changeSelectCategory();
                }
            },
            changeSelectCategory: function () {
                this.selectedFilter = null;

                let filters = [];
                if (this.selectedCategory.length) {
                    filters = this.selectedTypeObject.categories
                        .find((item) => item.id === this.lastSelectedCategory())
                        .filters;
                }
                else {
                    filters = this.selectedTypeObject.filters;
                }

                this.selectFilters = filters.map(filter => {
                    return this.filters.find((item) => item.id === filter.filter_id);
                });
            },
            dialogWorkWithFilter: function () {
                this.modalAlerts = [];
                this.resetFormAddFilter();

                this.titleDialogWorkWith = 'Управление фильтрами';
                this.visibleDialogWorkWithFilters = true;
            },
            getCategory: function (typeId, categoryId) {
                let type = this.getType(typeId);

                if (type.categories.length) {
                    return type.categories.find(item => item.id === categoryId) || {
                        name: null
                    }
                }
            },
            getType: function (id) {
                return this.types.find(item => item.id === id) || {
                    name: null
                };
            },
            setTypes: function () {
                ApiTypes.get().then((response) => {
                    this.$store.commit('updateTypes', response.data.types);
                    this.types = response.data.types;
                });
            },
            getFilter: function (id) {
                return this.filters.find(item => item.id === id) ||  {
                    name: null
                }
            },
            setFilters: function () {
                ApiFilters.get().then((response) => {
                    this.$store.commit('updateFilters', response.data.filters);
                    this.filters = response.data.filters;
                });
            },
            getTreeCategories: function () {
                if (this.selectedType !== null) {
                    let type = this.types.find((item) => item.id === this.selectedType);
                    let categories = type.categories.map((item) => {
                        if (item.parent_id === 1) {
                            item.parent_id = 0;
                        }
                        return item;
                    }).filter((item) => {
                        return item.id !== 1;
                    });
                    categories = arrayToTree(categories);

                    return categories;
                }
            },
            changeProductImageOnModal: function(image) {
                if (image === 'origin') {
                    this.productImageOnModal = this.workWithImage.origin;
                    this.currentProductImageOnModal = 'origin';
                    return true;
                }
                this.productImageOnModal = this.workWithImage.preview;
                this.currentProductImageOnModal = 'preview';
            },
            openModalCropImage: function() {
                this.modalAlerts = [];
                this.titleDialogWorkWith = 'Редактировать превью';
                this.visibleDialogWorkWithImage = false;
                this.initialImageCroppa = this.getFullPathImage(this.workWithImage.origin);
                if (this.imageCroppa !== null) {
                    this.imageCroppa.refresh();
                }
                this.visibleDialogWorkWithCropImage = true;
            },
            clickWorkWithCropImage: function () {
                if (!this.imageCroppa.hasImage()) {
                    this.$notify.error({
                        offset: 50,
                        title: 'Ошибка',
                        message: 'Отсутствует изображение'
                    });
                    return false;
                }

                this.imageCroppa.generateBlob((blob) => {
                    let formData = new FormData();

                    formData.append('image', blob, 'preview.png');
                    formData.append('id', this.workWithImage.id);
                    
                    ApiProductImages.updatePreview(formData).then((response) => {
                        if(response.data.status === 'success') {
                            this.$notify.success({
                                offset: 50,
                                title: 'Запрос выполнен',
                                message: response.data.message
                            });
                            let index = this.form.images.findIndex((item) => item.id === response.data.image.id);
                            let image = this.form.images[index];
                            image.preview = response.data.image.preview;
                            this.form.images.splice(index, 1, image);
                            index = this.imagesList.findIndex((item) => item.id === response.data.image.id);
                            image = this.imagesList[index];
                            image.url = this.getFullPathImage(response.data.image.preview);
                            this.imagesList.splice(index, 1, image);
                            this.changeOldForm(this.form);
                        }
                    }).catch((error) => {
                        this.modalAlerts = error.response.data.errors;
                        this.modalTypeAlerts = 'error';
                    });
                }, 'image/png', 1.0);
            },
            getFullPathImage: function(path) {
                return `/app/public/images/products/${path}`
            },
            handlePreviewImages: function (image) {
                this.workWithImage = this.form.images.find((item) => item.id === image.id);
                this.titleDialogWorkWith = 'Управление изображением';
                this.productImageOnModal = this.workWithImage.origin;
                this.currentProductImageOnModal = 'origin';
                this.visibleDialogWorkWithImage = true;
            },
            clickWorkWithImage: function() {
                this.$refs['formWorkWithImage'].validate((valid) => {
                    if (valid) {
                        ApiProductImages.update(this.workWithImage).then((response) => {
                            let index = this.form.images.findIndex((item) => item.id === response.data.image.id);
                            this.form.images.splice(index, 1, response.data.image);
                            this.form.images = helperArray.sort(this.form.images);

                            if (response.data.image.main_status === 1) {
                                this.form['main_image'] = response.data.image;
                            }

                            index = this.imagesList.findIndex((item) => item.id === response.data.image.id);
                            this.imagesList.splice(index, 1, {
                                name: response.data.image.preview,
                                url: this.getFullPathImage(response.data.image.preview),
                                id: response.data.image.id,
                                sorting_order: response.data.image.sorting_order,
                            });
                            this.imagesList = helperArray.sort(this.imagesList);

                            this.changeOldForm(this.form);

                            this.$notify.success({
                                offset: 50,
                                title: 'Запрос успешно выполнен',
                                message: response.data.message
                            });
                        }).catch((error) => {
                            this.modalAlerts = error.response.data.errors;
                            this.modalTypeAlerts = 'error';
                        })
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
            changeDescription: function() {
                this.form.description = (this.description.length)
                        ? this.description.replace(/(\")[\.\/]{2,}/, '$1/')
                        : '';
            },
            uploadImages: function(form) {
                let formData = new FormData();
                formData.append('product_id', this.form.id);
                formData.append('image', form.file);
                ApiProducts.uploadImages(formData).then((response) => {
                    if (response.data.status === 'success') {
                        let image = response.data.product_image;
                        this.imagesList.unshift({
                            name: response.data.image_name,
                            url: response.data.image_url,
                            id: image.id,
                            sorting_order: image.sorting_order
                        });
                        this.imagesList = helperArray.sort(this.imagesList);
                        this.$notify.success({
                            offset: 50,
                            title: 'Запрос успешно выполнен',
                            message: response.data.message
                        });
                        this.form.images.unshift(image);
                        this.changeOldForm(this.form);
                    }
                }).catch((error) => {
                    this.alertsUploadImages = error.response.data.errors;
                    this.typeAlerts = 'error';
                    setTimeout(() => this.alertsUploadImages = [], 3000);
                });
            },
            onChangeImages: function(image, imagesList) {
                imagesList.pop();
            },
            handleRemoveImages: function (image, removeImagesList) {
                ApiProducts.deleteImages({
                    product_id: this.form.id,
                    image_id: image.id
                }).then((response) => {
                    let index = this.imagesList.findIndex(item => item.id === image.id);
                    this.imagesList.splice(index, 1);
                    index = this.form.images.findIndex(item => item.id === image.id);
                    this.form.images.splice(index, 1);
                    this.changeOldForm(this.form);
                    this.$notify.error({
                        offset: 50,
                        title: 'Запрос успешно выполнен',
                        message: response.data.message
                    });
                }).catch((error) => {
                    removeImagesList = this.imagesList;
                    this.alertsUploadImages = error.response.data.errors;
                    this.typeAlerts = 'error';
                })
            },
            initTinymce: function () {
                this.description = this.form.description;
            },
            changeOldForm: function (data) {
                this.oldForm = data;
            },
            setDataWhenCreating: function(product) {
                this.form = product;
                if (this.form.images.length) {
                    this.form.images.forEach(image => {
                        this.imagesList.push({
                            name: image.preview,
                            url: `/app/public/images/products/${image.preview}`,
                            id: image.id,
                            sorting_order: image.sorting_order,
                        });
                    });
                    this.imagesList = helperArray.sort(this.imagesList);
                }
                this.oldForm = _.cloneDeep(this.form);
            },
            setDataToStore: function (data = null) {
                let currentData = (data === null) ? this.oldForm : data;
                let products = this.products;
                if (products.data !== undefined && products.data.length > 0) {
                    let index = products.data.findIndex((item) => item.id === this.currentRoute.params.id);
                    products.data[index] = currentData;
                    this.$store.commit('updateProducts', products);
                }
            },
            defaultFormData: function () {
                return {
                    id: null,
                    article: '',
                    name: '',
                    slug: '',
                    description: '',
                    preview_description: '',
                    price: 0,
                    discount: null,
                    status: 1,
                    data: null
                }
            },
            setBreadcrumbElements: function () {
                this.breadcrumbElements = [
                    {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
                    {href: this.$router.resolve({name: 'products-list'}).href, title: helperRouter.getRouteByName(this.$router, 'products-list').meta.title},
                    {href: '', title: this.pageTitle}
                ];
            },
            onSubmit: function () {
                this.$refs['formWorkWithModel'].validate((valid) => {
                    if (valid) {
                        if (this.currentRoute.name === 'products-update') {
                            ApiProducts.update(this.$route.params.id, this.form).then((response) => {
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });

                                this.oldForm = response.data.product;
                                this.setDataToStore(response.data.product);
                            }).catch((error) => {
                                this.alerts = error.response.data.errors;
                                this.typeAlerts = 'error';
                            });
                        }
                        else {
                            ApiProducts.create(this.form).then((response) => {
                                let products = this.products;
                                if (products.data !== undefined && products.data.length) {
                                    products.data.unshift(response.data.product);
                                    this.$store.commit('updateProducts', products);
                                }
                                this.$notify.success({
                                    offset: 50,
                                    title: 'Запрос успешно выполнен',
                                    message: response.data.message
                                });
                                this.$router.push({name: 'products-list'});
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
                this.pageTitle = helperRouter.getRouteByName(this.$router, 'products-create').meta.title;
                this.form = this.defaultFormData();
                this.setBreadcrumbElements();
                this.currentRoute = this.$router.currentRoute;
            },
            'form.name': function (val) {
                if (this.countChangesSlug !== 0) {
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
            if (this.currentRoute.name === 'products-update') {
                this.setDataToStore();
            }
        }
    }
</script>

<style scoped>
    .upload-images {
        padding: 25px;
    }
    .el-upload__tip {
        padding-top: 5px;
    }
</style>
