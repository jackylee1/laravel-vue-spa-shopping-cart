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
        <el-form-item label="Промо-код" prop="code">
          <el-input :disabled="true" type="text" v-model="form.code" placeholder="Введите Промо-код"></el-input>
        </el-form-item>

        <el-form-item label="Тип" prop="type">
          <el-select v-model="form.type" placeholder="Выберите Тип">
            <el-option
                v-for="item in this.selectListPromotionalCodeTypes"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item v-if="form.type === 0" label="Процент скидки" prop="discount">
          <el-input type="text" v-model="form.discount" placeholder="Введите процент скидки"></el-input>
        </el-form-item>
        <el-form-item v-else label="Денежное значение" prop="cash_value">
          <el-input type="text" v-model="form.cash_value" placeholder="Введите Денежное значение"></el-input>
        </el-form-item>

        <el-form-item label="Статус" prop="status">
          <el-select v-model="form.status" placeholder="Выберите статус">
            <el-option
                v-for="item in this.selectListPromotionalCodeStatuses"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>

        <PageElementsAlerts :alerts="alerts" :type="typeAlerts"/>

        <el-form-item>
          <el-button type="primary" @click="onSubmit">{{ submitName }}</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
  import * as ApiPromotionalCodes from '../../../app/admin/api/PromotionalCodes';
  import * as helperRouter from '../../../app/helpers/router';

  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import {generatingValidationMessage} from '../../../helpers/generatingValidationMessage';

  export default {
    name: 'users-work-with-model',
    data() {
      return {
        pageTitle: '',
        form: this.defaultFormData(),
        oldForm: null,
        rules: {
          code: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {max: 191, min: 3, message: generatingValidationMessage('length', [255, 3]), trigger: ['blur', 'change']}
          ],
          status: [
            {required: true, message: generatingValidationMessage('required'), trigger: ['blur', 'change']},
            {
              type: 'enum',
              enum: [1, 0],
              message: generatingValidationMessage('enum', [1, 2])
            }
          ],
          cash_value: [
            {pattern: /^[0-9]*$/g, message: 'Только целочисленное значение', trigger: ['blur', 'change']}
          ],
          discount: [
            {pattern: /^(?:100|[1-9]?[0-9])$/, message: 'Значение в этом поле должно быть от 0 до 100', trigger: ['blur', 'change']}
          ],
        },
        currentRoute: null,
        breadcrumbElements: [],
        submitName: null,
        alerts: [],
        typeAlerts: 'success'
      }
    },
    created() {
      this.currentRoute = this.$router.currentRoute;
      if (this.currentRoute.name === 'promotional-codes-update') {
        if (this.promotionalCodes.data !== undefined && this.promotionalCodes.data.length) {
          let promotionalCode = this.promotionalCodes.data.find((code) => code.id === this.$route.params.id);
          this.setDataWhenCreating(promotionalCode);
        }
        else {
          ApiPromotionalCodes.show(this.$route.params.id).then((response) => {
            this.setDataWhenCreating(response.data.promotional_code);
          });
        }
        this.submitName = 'Обновить';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'promotional-codes-update').meta.title;
      }
      else {
        ApiPromotionalCodes.getFree().then((response) => {
          this.form.code = response.data.promotional_code;
        });
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'promotional-codes-create').meta.title;
      }

      this.setBreadcrumbElements();
    },
    computed: {
      promotionalCodes() {
        return this.$store.getters.promotionalCodes;
      },
      selectListPromotionalCodeStatuses: function () {
        return this.$store.getters.selectDataListPromotionalCodeStatuses;
      },
      selectListPromotionalCodeTypes: function () {
        return this.$store.getters.selectDataListPromotionalCodeTypes;
      },
      usersStore: function () {
        return this.$store.getters.users;
      }
    },
    methods: {
      setDataWhenCreating: function(promotionalCode) {
        this.form = promotionalCode;
        this.oldForm = _.cloneDeep(this.form);
      },
      setDataToStore: function (data = null) {
        let currentData = (data === null) ? this.oldForm : data;
        let promotionalCodes = this.promotionalCodes;
        if (promotionalCodes.data) {
          let index = promotionalCodes.data.findIndex((code) => code.id === this.currentRoute.params.id);
          promotionalCodes.data[index] = currentData;
          if (this.usersStore.data !== undefined && this.usersStore.data.length) {
            let usersStore = this.usersStore;
            usersStore.data = usersStore.data.map(user => {
              if (user.promotional_codes.length) {
                user.promotional_codes = user.promotional_codes.map(item => {
                  if (item.promotional_code_id === this.form.id) {
                    item.promotional_code = currentData;
                  }
                  return item;
                });
              }
              return user;
            });
            this.$store.commit('updateUsers', usersStore);
          }
          this.$store.commit('updatePromotionalCodes', promotionalCodes);
        }
      },
      defaultFormData: function () {
        return {
          id: null,
          type: 0,
          cash_value: 0,
          code: '',
          status: 1,
          discount: 0,
        }
      },
      setBreadcrumbElements: function () {
        this.breadcrumbElements = [
          {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
          {href: this.$router.resolve({name: 'promotional-codes-list'}).href, title: helperRouter.getRouteByName(this.$router, 'promotional-codes-list').meta.title},
          {href: '', title: this.pageTitle}
        ];
      },
      onSubmit: function () {
        this.$refs['formWorkWithModel'].validate((valid) => {
          if (valid) {
            if (this.currentRoute.name === 'promotional-codes-update') {
              let self = this;
              ApiPromotionalCodes.update(this.$route.params.id, this.form).then((response) => {
                this.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });

                self.oldForm = response.data.promotional_code;
                self.setDataToStore(response.data.promotional_code);
              }).catch((error) => {
                self.alerts = error.response.data.errors;
                self.typeAlerts = 'error';
              });
            }
            else {
              let self = this;
              ApiPromotionalCodes.create(self.form).then((response) => {
                let promotionalCodes = self.promotionalCodes;
                if (promotionalCodes.data !== undefined && promotionalCodes.data.length) {
                  promotionalCodes.data.unshift(response.data.promotional_code);
                  self.$store.commit('updatePromotionalCodes', promotionalCodes);
                }
                self.$notify.success({
                  offset: 50,
                  title: 'Запрос успешно выполнен',
                  message: response.data.message
                });
                self.$router.push({name: 'promotional-codes-list'});
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
      }
    },
    components: {
      PageElementsBreadcrumb,
      PageElementsAlerts
    },
    watch: {
      '$route' (to, from) {
        this.submitName = 'Создать';
        this.pageTitle = helperRouter.getRouteByName(this.$router, 'promotional-codes-create').meta.title;
        this.form = this.defaultFormData();
        this.setBreadcrumbElements();
        this.currentRoute = this.$router.currentRoute;
      }
    },
    beforeDestroy() {
      if (this.currentRoute.name === 'promotional-codes-update') {
        this.setDataToStore();
      }
    }
  }
</script>