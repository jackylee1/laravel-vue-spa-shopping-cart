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
          <el-input type="text" v-model="form.name" placeholder="Введите Имя"></el-input>
        </el-form-item>

        <el-form-item label="SEO URL" prop="slug">
          <el-input type="text" v-model="form.slug" placeholder="SEO URL"></el-input>
        </el-form-item>

        <el-form-item label="Текущее изображение" v-if="form.image_preview !== null && form.image_preview !== undefined">
          <img width="100" height="auto" :src="'/app/public/images/type/'+form.image_preview">
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

        <el-form-item label="Показать на главной">
          <el-select v-model="form.show_on_index" placeholder="Показать на главной" prop="show_on_index">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Текущее изображение на главной" v-if="form.image_index_preview !== null && form.image_index_preview !== undefined">
          <img width="100" height="auto" :src="'/app/public/images/type/'+form.image_index_preview">
        </el-form-item>

        <el-form-item v-if="form.show_on_index" label="Изображение на главной" prop="image_index">
          <el-upload
              name="image"
              :multiple="false"
              :show-file-list="false"
              :on-change="changeDataInImageIndexField"
              :action="'windows.location.href'"
              :auto-upload="false">
            <el-button slot="trigger" size="small" type="primary">Выбрать изображение</el-button>
            <el-button style="margin-left: 10px;" size="small" type="danger" @click="resetIndexImage">Очистить</el-button>
            <div class="el-upload__tip" slot="tip">jpg/png/jpeg/gif/ изображения размером не более 2048kb</div>
            <div v-if="fileIndexName.length">Выбранное изображение: {{fileIndexName}}</div>
          </el-upload>
        </el-form-item>

        <el-form-item label="Показать в блоке Сертификаты">
          <el-select v-model="form.show_on_certificate" placeholder="Показать в блоке Сертификаты" prop="show_on_certificate">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Показать в шапке сайта?">
          <el-select v-model="form.show_on_header" placeholder="Показать в шапке сайта?" prop="show_on_header">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Показать в футере">
          <el-select v-model="form.show_on_footer" placeholder="Показать в футере" prop="show_on_footer">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Порядок сорт." prop="sorting_order">
          <el-input type="text" v-model="form.sorting_order" placeholder="Введите Порядок сорт."></el-input>
        </el-form-item>

        <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

        <el-form-item>
          <el-button-group>
            <el-button v-if="showFilters" type="default" @click="viewOnSite(null)"><i class="el-icon-view"></i></el-button>
            <el-button v-if="showFilters" type="default" @click="modalWorkWithFilters">Управление фильтрами</el-button>
            <el-button v-if="showTree" type="default" @click="modalCreateNode">Добавить категорию</el-button>
            <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
          </el-button-group>
        </el-form-item>
      </el-form>
    </div>

    <div class="ds-block" v-if="showTree">
      <el-row class="ds-description">
        <h3 class="text-center">Список категорий</h3>
        Наименование категории | Порядок сорт. | Операции
      </el-row>
      <el-tree class="ds-description"
               :data="renderTree()"
               node-key="id"
               default-expand-all
               :props="defaultProps"
               :expand-on-click-node="false">
                <span class="ds-tree-node" slot-scope="{ node }">
                  <span>{{ node.label }}</span>
                  <span>{{ node.data.sorting_order }}</span>
                  <span>
                    <el-button-group>
                      <el-button @click="viewOnSite(node.data)"
                        size="mini">
                        <i class="el-icon-view"></i>
                      </el-button>
                      <el-button type="primary"
                        @click="modalWorkWithFiltersCategory(node.data)"
                        size="mini">
                        <v-icon name="filter"></v-icon>
                      </el-button>
                      <el-button type="primary"
                        @click="modalEditNode(node.data)"
                        size="mini">
                        <i class="el-icon-edit"></i>
                      </el-button>
                      <el-button type="danger"
                        size="mini"
                        @click="modalDeleteNode(node.data)">
                        <i class="el-icon-delete"></i>
                      </el-button>
                    </el-button-group>
                  </span>
                </span>
      </el-tree>
    </div>

    <el-dialog :title="titleDialogWorkWith" :visible.sync="visibleDialogWorkWithNode">
      <el-form ref="formWorkWithNode"
               v-loading="loading"
               :rules="rulesNode"
               :model="workWithNode">
        <el-form-item label="Наименование" prop="name">
          <el-input v-model="workWithNode.name"></el-input>
        </el-form-item>
        <el-form-item label="SEO адрес" prop="slug">
          <el-input v-model="workWithNode.slug"></el-input>
        </el-form-item>

        <el-form-item label="Meta Заголовок" prop="m_title">
          <el-input type="text" v-model="workWithNode.m_title" placeholder="Введите Meta Заголовок"></el-input>
        </el-form-item>

        <el-form-item label="Meta описание" prop="m_description">
          <el-input
              type="textarea"
              :rows="3"
              placeholder="Введите Meta описание"
              v-model="workWithNode.m_description">
          </el-input>
        </el-form-item>

        <el-form-item label="Meta Ключевые слова" prop="m_keywords">
          <el-input type="text" v-model="workWithNode.m_keywords" placeholder="Введите Meta Ключевые слова"></el-input>
        </el-form-item>

        <el-form-item  label="Порядок сорт." prop="sorting_order">
          <el-input v-model="workWithNode.sorting_order"></el-input>
        </el-form-item>

        <template v-if="workWithNode.parent_id === 1">
          <el-form-item label="Отображать в шапке">
            <el-select v-model="workWithNode.show_on_header" placeholder="" prop="show_on_header">
              <el-option
                  v-for="item in this.selectBoolean"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-alert
              style="margin-bottom: 10px"
              title="Если выбрать Нет то при отрисовке категорий в шабке сайта будут видны только подкатегории"
              type="info"
              :closable="false">
          </el-alert>
        </template>

        <template v-if="workWithNode.parent_id === 1">
          <el-form-item label="Скрыть наименование?">
            <el-select v-model="workWithNode.hidden_name" placeholder="" prop="hidden_name">
              <el-option
                  v-for="item in this.selectBoolean"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-alert
                style="margin-bottom: 10px"
                title="Вы можете скрыть наименование родительской категории (учитывается в шапке сайта)"
                type="info"
                :closable="false">
            </el-alert>
          </el-form-item>

          <el-form-item label="Активная ссылка?">
            <el-select v-model="workWithNode.active_link" placeholder="" prop="active_link">
              <el-option
                  v-for="item in this.selectBoolean"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-alert
                style="margin-bottom: 10px"
                title="Активная ссылка - отвечает за то активная ли ссылка родительской категории в шапке сайта"
                type="info"
                :closable="false">
            </el-alert>
          </el-form-item>
        </template>

        <el-select v-model="workWithNode.parent_id" placeholder="Выберите родителя" prop="parent_id">
          <el-option
              v-for="item in renderSelectParent"
              :key="item.id"
              :label="item.name"
              :value="item.id">
          </el-option>
        </el-select>
      </el-form>
      <PageElementsAlerts :alerts="modalAlerts" :type="modalTypeAlerts"/>
      <span slot="footer" class="dialog-footer">
                <el-button-group>
                    <el-button @click="visibleDialogWorkWithNode = false">Отмена</el-button>
                    <el-button type="primary" @click="clickWorkWithNode">Сохранить</el-button>
                </el-button-group>
            </span>
    </el-dialog>

    <el-dialog :title="titleDialogWorkWith" :visible.sync="visibleDialogWorkWithFilters">
      <FiltersTableSelect :currentModel="currentModel"
                          :model="this.form"
                          v-on:selectFilter="filterActionHandler"/>
      <PageElementsAlerts :alerts="modalAlerts" :type="modalTypeAlerts"/>
      <span slot="footer" class="dialog-footer">
                <el-button @click="visibleDialogWorkWithFilters = false">Отмена</el-button>
            </span>
    </el-dialog>

    <el-dialog :title="titleDialogWorkWith" :visible.sync="visibleDialogWorkWithFiltersCategory">
      <FiltersTableSelect :currentModel="currentModel"
                          :model="this.workWithCategory"
                          v-on:selectFilter="filterActionHandlerCategory"/>
      <PageElementsAlerts :alerts="modalAlerts" :type="modalTypeAlerts"/>
      <span slot="footer" class="dialog-footer">
                <el-button @click="visibleDialogWorkWithFiltersCategory = false">Отмена</el-button>
            </span>
    </el-dialog>

    <el-dialog
        :title="deleteNodeTitleDialog"
        :visible.sync="deleteNodeDialogVisible"
        width="60%">
      <el-alert
          :title="deleteNodeTitleAlert"
          :type="deleteNodeTypeAlert"
          :closable="false">
      </el-alert>
      <span slot="footer" class="dialog-footer">
                <el-button-group>
                    <el-button @click="deleteNodeDialogVisible = false">Отмена</el-button>
                    <el-button type="primary" @click="deleteNode">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';
  import * as ApiTypes from '../../../app/admin/api/Types';
  import * as ApiCategories from '../../../app/admin/api/Categories';
  import { FiltersTableSelect } from '../Filters';
  import * as helperRouter from '../../../app/helpers/router';

  let arrayToTree = require('array-to-tree');
  let slugify = require('slugify');

  export default {
    name: 'types-work-with-model',
    created() {
      this.currentRoute = this.$router.currentRoute;
      if (this.currentRoute.name === 'types-update') {
        this.showTree = this.showFilters = true;
        if (this.types !== undefined && this.types.length) {
          let typeStore = this.types.find((type) => type.id === this.$route.params.id);
          this.form = typeStore;
          this.oldForm = _.cloneDeep(this.form);
          this.categories = typeStore.categories;
        }
        else {
          ApiTypes.show(this.$route.params.id).then((response) => {
            this.form = response.data.type;
            this.categories = response.data.type.categories;
            this.oldForm = _.cloneDeep(this.form);
          });
        }

        this.submitName = 'Обновить';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'types-update').meta.title;
      }
      else {
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'types-create').meta.title;
      }
      this.setBreadcrumbElements();
    },
    data() {
      return {
        rulesNode: {
          name: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
          ],
          slug: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
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
        deleteNodeDialogVisible: false,
        deleteNodeTypeAlert: null,
        deleteNodeTitleAlert: null,
        deleteNodeTitleDialog: null,
        modalAlerts: [],
        modalTypeAlerts: 'success',
        workWithNode: {},
        titleDialogWorkWith: '',
        visibleDialogWorkWithNode: false,
        visibleDialogWorkWithFilters: false,
        categories: [],
        defaultProps: {
          children: 'children',
          label: 'name'
        },
        fileName: '',
        fileIndexName: '',
        countChangesSlug: 0,
        showTree: false,
        showFilters: false,
        pageTitle: '',
        form: this.defaultFormData(),
        oldForm: null,
        rules: {
          name: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
          ],
          slug: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
          ],
          sorting_order: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {pattern: /^\d+$/, message: generatingValidationMessage('integer'), trigger: ['blur', 'change']}
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
        currentRoute: null,
        breadcrumbElements: [],
        submitName: null,
        alerts: [],
        typeAlerts: 'success',
        currentModel: 'types',
        workWithCategory: {},
        visibleDialogWorkWithFiltersCategory: false,
        oldParentId: null,
        loading: false
      }
    },
    computed: {
      types: function() {
        return this.$store.getters.types
      },
      selectBoolean: function () {
        return this.$store.getters.selectDataBoolean;
      },
      renderSelectParent: function() {
        let result = this.categories.filter((item) => {
          return item.parent_id === 1 || item.parent_id === 0;
        });
        if (!result.find((item) => item.id === 1)) {
          result.unshift({
            'name': 'Корневая категория',
            'id': 1
          });
        }
        return result;
      }
    },
    methods: {
      viewOnSite: function (item) {
        let url = location.protocol+'//'+location.hostname+'/catalog?type='+this.oldForm.slug;
        if (item !== null) {
          url += '&category='+item.slug;
        }
        window.open(url, '_blank');
      },
      changeDataInImageField: function(file, fileListt) {
        this.fileName = file.name;
        this.form.image = file.raw;
      },
      changeDataInImageIndexField: function(file, fileListt) {
        this.fileIndexName = file.name;
        this.form.image_index = file.raw;
      },
      resetImage: function() {
        this.form.image = null;
        this.fileName = '';
      },
      resetIndexImage: function() {
        this.form.image_index = null;
        this.fileIndexName = '';
      },
      changeOldForm: function(data) {
        this.oldForm = data;
      },
      filterActionHandler: function(filter) {
        ApiTypes.handleFilter({
          'type_id': this.form.id,
          'filter_id': filter.id
        }).then((response) => {
          response = response.data;
          if (response.status === 'success') {
            if (response.operation === 'add') {
              this.$notify.success({
                offset: 50,
                title: 'Запрос успешно выполнен',
                message: response.message
              });
              this.form.filters.unshift(response.create_model);
            }
            else {
              let index = this.form.filters.findIndex((item) => item.filter_id === filter.id);
              this.form.filters.splice(index, 1);
              this.$notify.error({
                offset: 50,
                title: 'Запрос успешно выполнен',
                message: response.message
              });
            }
            this.changeOldForm(this.form);
          }

        }).catch((error) => {
          this.alerts = error.response.data.errors;
          this.typeAlerts = 'error';
        });
      },
      sortCategories: function () {
        this.form.categories.sort(function(a, b) {
          return a.sorting_order - b.sorting_order;
        });
      },
      modalCreateNode: function () {
        this.titleDialogWorkWith = 'Добавить категорию';
        this.workWithNode = {
          name: '',
          sorting_order: 0,
          slug: '',
          parent_id: 1,
          show_on_header: 1,
          hidden_name: 0,
          active_link: 1,
          m_title: '',
          m_description: '',
          m_keywords: ''
        };
        this.visibleDialogWorkWithNode = true;
        this.modalAlerts = [];
      },
      modalWorkWithFilters: function() {
        this.currentModel = 'types';
        this.titleDialogWorkWith = `Управление фильтрами типа "${this.form.name}"`;
        this.visibleDialogWorkWithFilters = true;
        this.modalAlerts = [];
      },
      modalWorkWithFiltersCategory: function(category) {
        this.workWithCategory = category;
        this.currentModel = 'type_category';
        this.titleDialogWorkWith = `Управление фильтрами типа "${this.form.name}" категории "${category.name}"`;
        this.visibleDialogWorkWithFiltersCategory = true;

        this.modalAlerts = [];
      },
      filterActionHandlerCategory: function(filter) {
        ApiCategories.handleFilter({
          'category_id': this.workWithCategory.id,
          'filter_id': filter.id
        }).then((response) => {
          response = response.data;
          if (response.status === 'success') {
            let indexCategory = this.form.categories.findIndex((item) => item.id === this.workWithCategory.id);
            if (response.operation === 'add') {
              this.form.categories[indexCategory].filters.unshift(response.create_model);
              this.$notify.success({
                offset: 50,
                title: 'Запрос успешно выполнен',
                message: response.message
              });
            }
            else {
              let indexFilter = this.form.categories[indexCategory].filters.findIndex((item) => {
                return item.filter_id === filter.id;
              });
              this.form.categories[indexCategory].filters.splice(indexFilter, 1);
              this.$notify.error({
                offset: 50,
                title: 'Запрос успешно выполнен',
                message: response.message
              });
            }
            this.workWithCategory =  this.form.categories[indexCategory];
            this.changeOldForm(this.form);
          }

        }).catch((error) => {
          this.alerts = error.response.data.errors;
          this.typeAlerts = 'error';
        });
      },
      deleteNode: function () {
        ApiCategories.destroy(this.workWithNode.id).then((response) => {
          for (let i in response.data.remove_categories) {
            let index = this.categories.findIndex((item) => item.id === response.data.remove_categories[i]);
            this.form.categories.splice(index, 1);
          }
          this.oldForm = this.form;
          this.deleteNodeDialogVisible = false;
          this.$notify.success({
            offset: 50,
            title: 'Запрос успешно выполнен',
            message: response.data.message
          });
        });
      },
      modalDeleteNode: function (node) {
        this.workWithNode = node;
        this.deleteNodeTitleDialog = 'Подтверите удаление категории';
        this.deleteNodeTypeAlert = 'warning';
        this.deleteNodeTitleAlert = 'При удалении категории все ее подкатегории так же будут удалить. Товары при этом которые привязаны к этой категории остаются не тронуты.';
        this.deleteNodeDialogVisible = true;
      },
      renderTree: function() {
        let categories = this.categories.filter(function (item) {
          return item.id !== 1
        });
        return arrayToTree(categories)
      },
      modalEditNode: function (node) {
        this.titleDialogWorkWith = 'Изменить данные категории';
        this.workWithNode = node;
        this.oldParentId = this.workWithNode.parent_id;
        this.visibleDialogWorkWithNode = true;
        this.modalAlerts = [];
      },
      clickWorkWithNode: function () {
        this.$refs['formWorkWithNode'].validate((valid) => {
          if (valid) {
            this.loading = true;
            if (this.workWithNode.id !== undefined) {
              ApiCategories.update(this.workWithNode.id, this.workWithNode).then((response) => {
                if (this.oldParentId !== response.data.category.parent_id) {
                  this.$store.commit('updateProducts', []);
                  this.oldParentId = response.data.category.parent_id;
                }

                let category = response.data.category;
                let index = this.categories.findIndex((item) => item.id === category.id);
                this.categories[index] = category;
                this.form.categories = this.categories;
                this.sortCategories();
                this.oldForm = this.form;
                this.visibleDialogWorkWithNode = false;
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.loading = false;
              }).catch((error) => {
                this.modalAlerts = error.response.data.errors;
                this.modalTypeAlerts = 'error';
              });
            }
            else {
              this.workWithNode.type_id = this.form.id;
              if (this.workWithNode.parent_id === undefined) {
                this.workWithNode.parent_id = 1;
              }
              ApiCategories.create(this.workWithNode).then((response) => {
                this.form.categories.unshift(response.data.category);
                this.sortCategories();
                this.oldForm = this.form;
                this.visibleDialogWorkWithNode = false;
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.loading = false;
              }).catch((error) => {
                this.modalAlerts = error.response.data.errors;
                this.modalTypeAlerts = 'error';
                this.loading = false;
              });
            }
          }
        });
      },
      sortTypes: function (types) {
        return _.orderBy(types, 'sorting_order', 'asc');
      },
      setDataToStore: function (data = null) {
        let currentData = (data === null) ? this.oldForm : data;
        let types = this.types;
        if (types) {
          let index = types.findIndex((type) => type.id === this.currentRoute.params.id);
          types[index] = currentData;

          this.$store.commit('updateTypes', this.sortTypes(types));
        }
      },
      defaultFormData: function () {
        return {
          id: null,
          name: '',
          sorting_order: 0,
          slug: '',
          show_on_index: 0,
          show_on_footer: 0,
          show_on_certificate: 0,
          show_on_header: 1,
          image: null,
          image_preview: null,
          image_origin: null,
          image_index_preview: null,
          image_index_origin: null,
          m_title: '',
          m_description: '',
          m_keywords: ''
        }
      },
      setBreadcrumbElements: function () {
        this.breadcrumbElements = [
          {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
          {href: this.$router.resolve({name: 'types-list'}).href, title: helperRouter.getRouteByName(this.$router, 'types-list').meta.title},
          {href: '', title: this.pageTitle}
        ];
      },
      getFromData: function (objData) {
        let formData = new FormData();
        Object.keys(objData).forEach(key => {
          if (objData[key] !== null) {
            formData.append(key, objData[key]);
          }
        });

        return formData;
      },
      resetOldTypeCertificate: function (response) {
        if (this.types.length === 0) {
          return false;
        }

        let indexTypeCertificate = this.types.findIndex(item => item.show_on_certificate === 1 && response.data.type.id !== item.id);
        if (indexTypeCertificate !== -1) {
          let types = this.types;
          let type = types[indexTypeCertificate];
          type.show_on_certificate = 0;
          types.splice(indexTypeCertificate, 1, type);
          this.$store.commit('updateTypes', types);
        }
      },
      onSubmit: function () {
        this.$refs['formWorkWithModel'].validate((valid) => {
          if (valid) {
            if (this.currentRoute.name === 'types-update') {
              ApiTypes.update(this.getFromData(this.form)).then((response) => {
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.resetOldTypeCertificate(response);

                this.oldForm = response.data.type;
                this.form = response.data.type;

                this.sortCategories();
                this.setDataToStore(response.data.type);
              }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.typeAlerts = 'error';
              });
            }
            else {
              ApiTypes.create(this.getFromData(this.form)).then((response) => {
                this.resetOldTypeCertificate(response);

                let types = this.types;
                if (types.length) {
                  types.unshift(response.data.type);

                  this.$store.commit('updateTypes', this.sortTypes(types));
                }
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.$router.push({name: 'types-list'});
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
      FiltersTableSelect
    },
    watch: {
      '$route' (to, from) {
        this.showTree = false;
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'types-create').meta.title;
        this.form = this.defaultFormData();
        this.setBreadcrumbElements();
        this.currentRoute = this.$router.currentRoute;
      },
      'workWithNode.parent_id': function (val) {
        if (val === 1) {
          this.workWithNode.show_on_header = 1;
        }
      },
      'workWithNode.name': function (val) {
        if (val !== undefined) {
          this.workWithNode.slug = slugify(val, {
            replacement: '-',
            remove: null,
            lower: true
          })
        }
      },
      'workWithNode.slug': function (val) {
        if (val !== undefined) {
          this.workWithNode.slug = slugify(val, {
            replacement: '-',
            remove: null,
            lower: true
          })
        }
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
      if (this.currentRoute.name === 'types-update') {
        this.setDataToStore();
      }
    }
  }
</script>

<style>
  *, ::after, ::before {
    box-sizing: content-box;
  }
  .el-tree-node__content {
    padding-bottom: 15px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    height: 26px;
    cursor: pointer;
    padding-top: 5px;
  }
</style>