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

        <el-form-item label="Описание" prop="description">
          <el-input type="text" v-model="form.description" placeholder="Введите описание"></el-input>
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
  import * as ApiUtfRecords from '../../../app/admin/api/UtfRecords';
  import * as helperRouter from '../../../app/helpers/router';
  import * as helpersArray from '../../../app/admin/helpers/Array';

  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

  export default {
    name: 'utf-records-work-with-model',
    created() {
      this.currentRoute = this.$router.currentRoute;

      if (this.currentRoute.name === 'utf-records-update') {
        if (this.utfRecords.length) {
          let utfRecord = this.utfRecords.find((item) => item.id === this.$route.params.id);
          this.setDataWhenCreating(utfRecord);
        }
        else {
          ApiUtfRecords.show(this.$route.params.id).then((response) => {
            this.setDataWhenCreating(response.data.utfRecord);
          });
        }

        this.submitName = 'Обновить';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'utf-records-update').meta.title;
      }
      else {
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'utf-records-create').meta.title;
      }

      this.setBreadcrumbElements();
    },
    data() {
      return {
        pageTitle: '',
        form: this.defaultFormData(),
        oldForm: null,
        rules: {
          description: [
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
      utfRecords: function() {
        return this.$store.getters.utfRecords;
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

        let utfRecords = this.utfRecords;
        if (utfRecords.length) {
          let index = utfRecords.findIndex((item) => item.id === this.currentRoute.params.id);
          utfRecords[index] = currentData;
          utfRecords = helpersArray.sort(utfRecords);

          this.$store.commit('updateUtfRecords', utfRecords);
        }
      },
      defaultFormData: function () {
        return {
          id: null,
          description: '',
          sorting_order: 0,
        }
      },
      setBreadcrumbElements: function () {
        this.breadcrumbElements = [
          {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
          {href: this.$router.resolve({name: 'utf-records-list'}).href, title: helperRouter.getRouteByName(this.$router, 'utf-records-list').meta.title},
          {href: '', title: this.pageTitle}
        ];
      },
      onSubmit: function () {
        this.$refs['formWorkWithModel'].validate((valid) => {
          if (valid) {
            if (this.currentRoute.name === 'utf-records-update') {
              ApiUtfRecords.update(this.$route.params.id, this.form).then((response) => {
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.setDataWhenCreating(response.data.utf_record);
                this.setDataToStore(response.data.utf_record);
              }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.typeAlerts = 'error';
              });
            }
            else {
              ApiUtfRecords.create(this.form).then((response) => {
                let utfRecords = this.utfRecords;
                if (utfRecords.length) {
                  utfRecords.unshift(response.data.utf_record);
                  utfRecords = helpersArray.sort(utfRecords);

                  this.$store.commit('updateUtfRecords', utfRecords);
                }
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.$router.push({name: 'utf-records-list'});
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
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'utf-records-create').meta.title;
        this.form = this.defaultFormData();
        this.setBreadcrumbElements();
        this.currentRoute = this.$router.currentRoute;
      },
    },
    beforeDestroy() {
      if (this.currentRoute.name === 'utf-records-update') {
        this.setDataToStore();
      }
    }
  }
</script>