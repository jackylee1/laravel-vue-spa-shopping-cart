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
  import * as ApiTextBlockTitles from '../../../app/admin/api/TextBlockTitles';
  import * as helperRouter from '../../../app/helpers/router';
  import * as helpersArray from '../../../app/admin/helpers/Array';

  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

  export default {
    name: 'text-block-titles-work-with-model',
    created() {
      this.currentRoute = this.$router.currentRoute;

      if (this.currentRoute.name === 'text-block-titles-update') {
        if (this.textBlockTitles.length) {
          let text_block_title = this.textBlockTitles.find((item) => item.id === this.$route.params.id);
          this.setDataWhenCreating(text_block_title);
        }
        else {
          ApiTextBlockTitles.show(this.$route.params.id).then((response) => {
            this.setDataWhenCreating(response.data.text_block_title);
          });
        }

        this.submitName = 'Обновить';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'text-block-titles-update').meta.title;
      }
      else {
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'text-block-titles-create').meta.title;
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
        },
        currentRoute: null,
        breadcrumbElements: [],
        submitName: null,
        alerts: [],
        typeAlerts: 'success',
      }
    },
    computed: {
      textBlockTitles: function() {
        return this.$store.getters.textBlockTitles;
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

        let text_block_titles = this.textBlockTitles;
        if (text_block_titles.length) {
          let index = text_block_titles.findIndex((item) => item.id === this.currentRoute.params.id);
          text_block_titles[index] = currentData;
          text_block_titles = helpersArray.sort(text_block_titles);

          this.$store.commit('updateTextBlockTitles', text_block_titles);
        }
      },
      defaultFormData: function () {
        return {
          id: null,
          title: '',
          sorting_order: 0,
        }
      },
      setBreadcrumbElements: function () {
        this.breadcrumbElements = [
          {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
          {href: this.$router.resolve({name: 'text-block-titles-list'}).href, title: helperRouter.getRouteByName(this.$router, 'text-block-titles-list').meta.title},
          {href: '', title: this.pageTitle}
        ];
      },
      onSubmit: function () {
        this.$refs['formWorkWithModel'].validate((valid) => {
          if (valid) {
            if (this.currentRoute.name === 'text-block-titles-update') {
              ApiTextBlockTitles.update(this.$route.params.id, this.form).then((response) => {
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.setDataWhenCreating(response.data.text_block_title);
                this.setDataToStore(response.data.text_block_title);
              }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.typeAlerts = 'error';
              });
            }
            else {
              ApiTextBlockTitles.create(this.form).then((response) => {
                let text_block_titles = this.textBlockTitles;
                if (text_block_titles.length) {
                  text_block_titles.unshift(response.data.text_block_title);
                  text_block_titles = helpersArray.sort(text_block_titles);

                  this.$store.commit('updateTextBlockTitles', text_block_titles);
                }
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.$router.push({name: 'text-block-titles-list'});
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
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'text-block-titles-create').meta.title;
        this.form = this.defaultFormData();
        this.setBreadcrumbElements();
        this.currentRoute = this.$router.currentRoute;
      },
    },
    beforeDestroy() {
      if (this.currentRoute.name === 'text-block-titles-update') {
        this.setDataToStore();
      }
    }
  }
</script>