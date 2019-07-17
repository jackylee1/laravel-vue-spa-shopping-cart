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

        <el-form-item label="E-Mail" prop="email">
          <el-input type="text" v-model="form.email" placeholder="Введите E-Mail"></el-input>
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
  import * as ApiSubscribes from '../../../app/admin/api/Subscribes';
  import * as helperRouter from '../../../app/helpers/router';

  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import { generatingValidationMessage } from '../../../helpers/generatingValidationMessage';

  export default {
    name: 'subscribes-work-with-model',
    created() {
      this.currentRoute = this.$router.currentRoute;

      if (this.currentRoute.name === 'subscribes-update') {
        if (this.subscribes.data !== undefined && this.subscribes.data.length) {
          let subscribe = this.subscribes.data.find((item) => item.id === this.$route.params.id);
          if (subscribe.read_status === 0) {
            ApiSubscribes.updateReadStatus({'id': subscribe.id}).then((res) => {
              if (res.data.status === 'success') {
                subscribe.read_status = 1;
                this.oldForm = subscribe;
                this.updateNewSubscribes();
              }
            });
          }
          this.setDataWhenCreating(subscribe);
        }
        else {
          ApiSubscribes.show(this.$route.params.id).then((response) => {
            if (response.data.deduct_from_notifications) {
              this.updateNewSubscribes();
            }
            this.setDataWhenCreating(response.data.subscribe);
          });
        }

        this.submitName = 'Обновить';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'subscribes-update').meta.title;
      }
      else {
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'subscribes-create').meta.title;
      }

      this.setBreadcrumbElements();
    },
    data() {
      return {
        pageTitle: '',
        form: this.defaultFormData(),
        oldForm: null,
        rules: {
          email: [
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']},
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {type: 'email', message: generatingValidationMessage('email'), trigger: ['blur', 'change']}
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
      subscribes: function() {
        return this.$store.getters.subscribes;
      },
      newSubscribes: function () {
        return this.$store.getters.newSubscribes;
      }
    },
    methods: {
      updateNewSubscribes: function () {
        let newNotification = this.newSubscribes;
        this.$store.commit('updateNewSubscribes', newNotification - 1);
      },
      changeOldForm: function (data) {
        this.oldForm = data;
      },
      setDataWhenCreating: function(data) {
        this.form = data;
        this.oldForm = _.cloneDeep(this.form);
      },
      setDataToStore: function (data = null) {
        let currentData = (data === null) ? this.oldForm : data;

        let subscribes = this.subscribes;
        if (subscribes.data !== undefined && subscribes.data.length) {
          let index = subscribes.data.findIndex((item) => item.id === this.currentRoute.params.id);
          subscribes.data[index] = currentData;
          this.$store.commit('updateSubscribes', subscribes);
        }
      },
      defaultFormData: function () {
        return {
          id: null,
          email: '',
          read_status: 0
        }
      },
      setBreadcrumbElements: function () {
        this.breadcrumbElements = [
          {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
          {href: this.$router.resolve({name: 'subscribes-list'}).href, title: helperRouter.getRouteByName(this.$router, 'subscribes-list').meta.title},
          {href: '', title: this.pageTitle}
        ];
      },
      onSubmit: function () {
        this.$refs['formWorkWithModel'].validate((valid) => {
          if (valid) {
            if (this.currentRoute.name === 'subscribes-update') {
              ApiSubscribes.update(this.$route.params.id, this.form).then((response) => {
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.setDataWhenCreating(response.data.subscribe);
                this.setDataToStore(response.data.subscribe);
              }).catch((error) => {
                this.alerts = error.response.data.errors;
                this.typeAlerts = 'error';
              });
            }
            else {
              ApiSubscribes.create(this.form).then((response) => {
                this.oldForm = response.data.subscribe;
                let subscribes = this.subscribes;
                if (subscribes.data !== undefined && subscribes.data.length) {
                  subscribes.data.unshift(response.data.subscribe);
                  this.$store.commit('updateSubscribes', subscribes);
                }

                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                this.$router.push({name: 'subscribes-list'});
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
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'subscribes-create').meta.title;
        this.form = this.defaultFormData();
        this.setBreadcrumbElements();
        this.currentRoute = this.$router.currentRoute;
      },
    },
    beforeDestroy() {
      if (this.currentRoute.name === 'subscribes-update') {
        this.setDataToStore();
      }
    }
  }
</script>