<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <div class="ds-block" v-on:click="alerts = []">
      <el-form label-position="right" class="ds-source"
               ref="formWorkWithModel"
               :rules="rules"
               @keydown.enter="onSubmit"
               :model="form"
               label-width="200px">
        <el-form-item label="Текущее изображение" v-if="form.image_preview !== null">
          <img width="250" height="auto" :src="'/app/public/images/slider/'+form.image_preview">
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

        <el-form-item label="Заголовок" prop="title">
          <el-input type="text" v-model="form.title" placeholder="Введите Заголовок"></el-input>
        </el-form-item>

        <el-form-item label="Описание" prop="description">
          <tinymce id="description" v-model="description"
                   :other_options="optionsTinymce"
                   v-on:editorChange="this.changeDescription"
                   v-on:editorInit="initTinymce"></tinymce>
        </el-form-item>

        <el-form-item label="Ссылка на кнопке" prop="url">
          <el-input type="text" v-model="form.url" placeholder="Введите Ссылку на кнопке"></el-input>
        </el-form-item>

        <el-form-item label="Выравнивание заголовка">
          <el-select v-model="form.title_align" placeholder="Выравнивание заголовка" prop="title_align">
            <el-option
                v-for="item in this.selectAlign"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="Цвет заголовка" prop="title_color">
          <el-color-picker v-model="form.title_color"></el-color-picker>
        </el-form-item>

        <el-form-item label="Выравнивание кнопки">
          <el-select v-model="form.btn_align" placeholder="Выравнивание кнопки" prop="btn_align">
            <el-option
                v-for="item in this.selectAlign"
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
          <el-button type="primary" @click="onSubmit">{{submitName}}</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
  import * as ApiSliders from '../../../app/admin/api/Sliders';
  import * as helperRouter from '../../../app/helpers/router';
  import * as helpersArray from '../../../app/admin/helpers/Array';

  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

  export default {
    name: 'sliders-work-with-model',
    created() {
      this.currentRoute = this.$router.currentRoute;

      if (this.currentRoute.name === 'sliders-update') {
        if (this.sliders.data !== undefined && this.sliders.data.length) {
          let slider = this.sliders.data.find((item) => item.id === this.$route.params.id);
          this.setDataWhenCreating(slider);
        }
        else {
          ApiSliders.show(this.$route.params.id).then((response) => {
            this.setDataWhenCreating(response.data.slider);
          });
        }

        this.submitName = 'Обновить';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'sliders-update').meta.title;
      }
      else {
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'sliders-create').meta.title;
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
              required: (this.$router.currentRoute.name !== 'sliders-update'),
              message: generatingValidationMessage('required'), trigger: ['blur', 'change']
            },
          ],
          title: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
          ],
          url: [
            {type: 'url', message: generatingValidationMessage('url'), trigger: ['blur', 'change']},
            {max: 191, min: 1, message: generatingValidationMessage('length', [191, 1]), trigger: ['blur', 'change']}
          ],
          sorting_order: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {pattern: /^\d{1,3}$/, message: 'Значение в этом поле не должно быть от 0 до 999', trigger: ['blur', 'change']}
          ],
          description: [
            {max: 50000, min: 0, message: generatingValidationMessage('length', [50000, 0]), trigger: ['blur', 'change']},
          ],
        },
        optionsTinymce: {
          language_url: '/js/tinymce/langs/ru.js'
        },
        description: null,
        currentRoute: null,
        breadcrumbElements: [],
        submitName: null,
        alerts: [],
        typeAlerts: 'success',
      }
    },
    computed: {
      sliders: function() {
        return this.$store.getters.sliders;
      },
      selectAlign: function () {
        return this.$store.getters.selectDataAlign;
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
      changeDataInImageField: function(file, fileListt) {
        this.fileName = file.name;
        this.form.image = file.raw;
      },
      changeDescription: function() {
        this.form.description = (this.description.length)
          ? this.description.replace(/(\")[\.\/]{2,}/, '$1/')
          : '';
      },
      initTinymce: function () {
        this.description = this.form.description;
      },
      setDataWhenCreating: function(slider) {
        this.form = slider;
        this.oldForm = _.cloneDeep(this.form);
      },
      setDataToStore: function (data = null) {
        let currentData = (data === null) ? this.oldForm : data;

        let sliders = this.sliders;
        if (sliders.data) {
          let index = sliders.data.findIndex((item) => item.id === this.currentRoute.params.id);
          sliders.data[index] = currentData;
          sliders.data = helpersArray.sort(sliders.data);
          this.$store.commit('updateSliders', sliders);
        }
      },
      defaultFormData: function () {
        return {
          id: null,
          url: '',
          title: '',
          sorting_order: 0,
          description: '',
          image: {},
          image_preview: null,
          image_origin: null,
          title_align: 'left',
          btn_align: 'left',
          title_color: '#fff'
        }
      },
      setBreadcrumbElements: function () {
        this.breadcrumbElements = [
          {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
          {href: this.$router.resolve({name: 'sliders-list'}).href, title: helperRouter.getRouteByName(this.$router, 'sliders-list').meta.title},
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
            if (this.currentRoute.name === 'sliders-update') {
              let self = this;
              ApiSliders.update(this.$route.params.id, this.getFromData()).then((response) => {
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.setDataWhenCreating(response.data.slider);
                self.setDataToStore(response.data.slider);
              }).catch((error) => {
                self.alerts = error.response.data.errors;
                self.typeAlerts = 'error';
              });
            }
            else {
              let self = this;
              ApiSliders.create(this.getFromData()).then((response) => {
                let sliders = self.$store.getters.sliders;
                if (sliders.data !== undefined && sliders.data.length) {
                  sliders.data.unshift(response.data.slider);
                  sliders.data = helpersArray.sort(sliders.data);
                  self.$store.commit('updateSliders', sliders);
                }
                self.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                self.$router.push({name: 'sliders-list'});
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
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'sliders-create').meta.title;
        this.form = this.defaultFormData();
        this.setBreadcrumbElements();
        this.currentRoute = this.$router.currentRoute;
      },
    },
    beforeDestroy() {
      if (this.currentRoute.name === 'sliders-update') {
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