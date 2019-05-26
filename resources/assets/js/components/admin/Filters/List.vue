<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <div class="ds-block">
      <el-row class="ds-description">
        <h3 class="text-center">Список фильтров</h3>
        <el-row>
          <el-button type="primary" @click="modalCreateNode">Добавить</el-button>
        </el-row>
        <p style="padding-top: 15px">Наименование фильтра | Тип | Порядок сорт. | Операции</p>
      </el-row>
      <el-tree class="ds-description"
               :data="renderTree()"
               node-key="id"
               default-expand-all
               :props="defaultProps"
               :expand-on-click-node="false">
        <span class="ds-tree-node" slot-scope="{ node }">
          <span>{{ node.label }} (ID: {{node.data.id}}) </span>
          <span>{{ getTypeLabelByValue(node.data.type) }}</span>
          <span>{{ node.data.sorting_order }}</span>
          <span>
            <el-button-group>
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

    <el-dialog :title="titleDialogWorkWithNode" :visible.sync="visibleDialogWorkWithNode">
      <el-form ref="formWorkWithNode"
               :rules="rulesNode"
               :model="workWithNode">
        <el-form-item label="Наименование" prop="name">
          <el-input v-model="workWithNode.name"></el-input>
        </el-form-item>

        <el-form-item label="SEO адрес" prop="slug">
          <el-input v-model="workWithNode.slug"></el-input>
        </el-form-item>

        <el-form-item  label="Порядок сорт." prop="sorting_order">
          <el-input v-model="workWithNode.sorting_order"></el-input>
        </el-form-item>

        <el-form-item label="Текущее изображение" v-if="workWithNode.image_preview !== null && workWithNode.image_preview !== undefined">
          <img width="100" height="auto" :src="'/app/public/images/filter/'+workWithNode.image_preview">
        </el-form-item>

        <el-form-item label="Изображение фильтра" prop="image">
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

        <el-form-item v-if="workWithNode.type !== 0" label="Показать на главной">
          <el-select v-model="workWithNode.show_on_index" placeholder="Показать на главной" prop="show_on_index">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item v-if="workWithNode.type !== 0" label="Показать в шапке">
          <el-select v-model="workWithNode.show_on_header" placeholder="Показать в шапке" prop="show_on_header">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item v-if="workWithNode.type === 0" label="Показать изображение?">
          <el-select v-model="workWithNode.show_image" placeholder="Показать изображение?" prop="show_image">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item  v-if="workWithNode.type === 0">
          <el-alert :closable="false"
                    title="Показать изображение - учитывается в случае если родительский фильтр Отображается на главной или Отображается в шапке"
                    type="info">
          </el-alert>
        </el-form-item>

        <el-form-item v-if="workWithNode.type !== 0" label="Показать в футере">
          <el-select v-model="workWithNode.show_on_footer" placeholder="Показать в шапке" prop="show_on_footer">
            <el-option
                v-for="item in this.selectBoolean"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item  label="Выберите тип фильтра">
          <el-select v-model="workWithNode.type" placeholder="Выберите тип" prop="type">
            <el-option
                v-for="item in this.selectFilterTypes"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item v-if="workWithNode.type === 0" label="Выберите родительский фильтр">
          <template>
            <el-select v-model="workWithNode.parent_id" placeholder="Выберите родительский фильтр" prop="parent_id">
              <el-option
                  v-for="item in getParentFilters"
                  :key="item.id"
                  :label="(item.id === 0) ? `${item.name}` : `${item.name} (ID: ${item.id}, SEO: ${item.slug})`"
                  :value="item.id">
              </el-option>
            </el-select>
          </template>
        </el-form-item>
      </el-form>
      <PageElementsAlerts :alerts="modalAlerts" :type="modalTypeAlerts"/>
      <span slot="footer" class="dialog-footer">
                <el-button-group>
                    <el-button @click="visibleDialogWorkWithNode = false">Отмена</el-button>
                    <el-button type="primary" @click="clickWorkWithNode">Сохранить</el-button>
                </el-button-group>
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
                <el-button @click="deleteNodeDialogVisible = false">Отмена</el-button>
                <el-button type="primary" @click="deleteNode">Подтверждаю</el-button>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';
  import * as ApiFilters from '../../../app/admin/api/Filters';
  import * as HelpersArray from '../../../app/admin/helpers/Array';
  import * as helperRouter from '../../../app/helpers/router';

  let arrayToTree = require('array-to-tree');
  let slugify = require('slugify');

  export default {
    name: 'filters',
    mounted() {
      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'filters'}).href, title: this.$router.currentRoute.meta.title},
      ];
      if (this.filtersStore.length) {
        this.filters = this.filtersStore;
      }
      else {
        ApiFilters.get().then((response) => {
          this.filters = response.data.filters;
          this.sortFilters();
          this.updateFiltersStore();
        });
      }
    },
    computed: {
      filtersStore: function () {
        return this.$store.getters.filters;
      },
      selectFilterTypes: function () {
        return this.$store.getters.selectDataFilterTypes;
      },
      selectBoolean: function () {
        return this.$store.getters.selectDataBoolean;
      },
      getParentFilters: function () {
        let filters = [];
        if (this.filters.length) {
          filters = this.filters.filter(function (item) {
            return item.parent_id === 0
          });
          filters = this.pushParentItemInFilters(filters);
        }
        else {
          filters = this.pushParentItemInFilters(filters);
        }
        return filters;
      },
    },
    data() {
      return {
        breadcrumbElements: [],
        titleDialogWorkWithNode: '',
        visibleDialogWorkWithNode: false,
        rulesNode: {
          name: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 1, message: generatingValidationMessage('length', [255, 1]), trigger: ['blur', 'change']}
          ],
          slug: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 1, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
          ],
          sorting_order: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {pattern: /^\d+$/, message: generatingValidationMessage('integer'), trigger: ['blur', 'change']}
          ],
        },
        workWithNode: {},
        modalAlerts: [],
        modalTypeAlerts: 'success',
        filters: [],
        defaultProps: {
          children: 'children',
          label: 'name'
        },
        deleteNodeDialogVisible: false,
        deleteNodeTypeAlert: null,
        deleteNodeTitleAlert: null,
        deleteNodeTitleDialog: null,
        fileName: ''
      }
    },
    methods: {
      resetImage: function() {
        this.workWithNode.image = null;
        this.fileName = '';
      },
      changeDataInImageField: function(file, fileListt) {
        this.fileName = file.name;
        this.workWithNode.image = file.raw;
      },
      getFromData: function () {
        let formData = new FormData();
        Object.keys(this.workWithNode).forEach(key => {
          if (this.workWithNode[key] !== null) {
            formData.append(key, this.workWithNode[key]);
          }
        });

        return formData;
      },
      pushParentItemInFilters: function (filters) {
        filters.unshift({
          id: 0,
          name: 'Родителький элемент'
        });
        return filters;
      },
      sortFilters: function () {
        this.filters =  HelpersArray.sort(this.filters);
      },
      getTypeLabelByValue: function (value) {
        let label = '';
        let index = this.selectFilterTypes.findIndex((item) => item.value === value);
        if (this.selectFilterTypes[index] !== undefined) {
          label = this.selectFilterTypes[index].label;
        }
        return label;
      },
      modalCreateNode: function () {
        this.titleDialogWorkWithNode = 'Добавить фильтр';
        this.workWithNode = {
          sorting_order: 0,
          type: 1,
          parent_id: 0,
          slug: '',
          show_on_index: 0,
          show_on_header: 0,
          show_on_footer: 0,
          show_image: 1,
          image: null,
          image_preview: null,
          image_origin: null,
        };
        this.visibleDialogWorkWithNode = true;
        this.modalAlerts = [];
      },
      modalEditNode: function (node) {
        this.titleDialogWorkWithNode = 'Изменить данные Фильтра';
        this.workWithNode = node;
        this.visibleDialogWorkWithNode = true;
        this.modalAlerts = [];
        this.fileName = '';
      },
      modalDeleteNode: function (node) {
        this.workWithNode = node;
        this.deleteNodeTitleDialog = 'Подтверите удаление фильтра';
        this.deleteNodeTypeAlert = 'warning';
        this.deleteNodeTitleAlert = 'При удалении фильтра все ее подфильтры так же будут удалить. Товары при этом которые привязаны к этим фильтрам остаются не тронуты.';
        this.deleteNodeDialogVisible = true;
      },
      updateFiltersStore: function () {
        this.$store.commit('updateFilters', this.filters);
      },
      clickWorkWithNode: function () {
        this.$refs['formWorkWithNode'].validate((valid) => {
          if (valid) {
            if (this.workWithNode.id !== undefined) {
              ApiFilters.update(this.getFromData()).then((response) => {
                let filter = response.data.filter;
                let index = this.filters.findIndex((item) => item.id === filter.id);
                this.filters[index] = filter;
                this.sortFilters();
                this.updateFiltersStore();
                this.visibleDialogWorkWithNode = false;
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
              }).catch((error) => {
                this.modalAlerts = error.response.data.errors;
                this.modalTypeAlerts = 'error';
              });
            }
            else {
              if (this.workWithNode.parent_id === undefined) {
                this.workWithNode.parent_id = 0;
              }
              ApiFilters.create(this.getFromData()).then((response) => {
                this.filters.unshift(response.data.filter);
                this.sortFilters();
                this.updateFiltersStore();
                this.visibleDialogWorkWithNode = false;

                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
              }).catch((error) => {
                this.modalAlerts = error.response.data.errors;
                this.modalTypeAlerts = 'error';
              });
            }
          }
        });
      },
      deleteNode: function () {
        ApiFilters.destroy(this.workWithNode.id).then((response) => {
          for (let i in response.data.remove_filters) {
            let index = this.filters.findIndex((item) => item.id === response.data.remove_filters[i]);
            this.filters.splice(index, 1);
          }
          this.deleteNodeDialogVisible = false;
          this.updateFiltersStore();
          this.$notify.success({
            offset: 50,
            title: 'Запрос успешно выполнен',
            message: response.data.message
          });
        });
      },
      renderTree: function () {
        let filters = [];

        if (this.filters.length) {
          filters = this.filters;
        }

        return arrayToTree(filters)
      },
    },
    components: {
      PageElementsBreadcrumb,
      PageElementsAlerts
    },
    watch: {
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
      'workWithNode.type': function (val) {
        if (val !== 0) {
          this.workWithNode.parent_id = 0;
        }
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