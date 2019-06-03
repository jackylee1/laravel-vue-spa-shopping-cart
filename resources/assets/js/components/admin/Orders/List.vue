<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <el-form :model="formSearch" class="ds-query-form" label-width="250px">
      <el-form-item label="ID Заказа">
        <el-input v-model="formSearch.id"
                  placeholder="">
        </el-input>
      </el-form-item>
      <el-form-item label="Фамилия">
        <el-input v-model="formSearch.user_surname"
                  placeholder="Введите Фамилию">
        </el-input>
      </el-form-item>
      <el-form-item label="Имя">
        <el-input v-model="formSearch.user_name"
                  placeholder="Введите Имя">
        </el-input>
      </el-form-item>
      <el-form-item label="ID Пользователя">
        <el-input v-model="formSearch.user_id"
                  placeholder="Введите ID пользователя">
        </el-input>
      </el-form-item>
      <el-form-item label="Только новые заказы">
        <el-select
            v-model="formSearch.only_new"
            placeholder="Только новые заказы">
          <el-option
              v-for="item in this.selectBoolean"
              :key="item.value"
              :label="item.label"
              :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button-group>
          <el-button type="default" @click="onResetSearch">
            Сбросить
          </el-button>
          <el-button type="primary" @click="onSubmitSearch">
            <i class="el-icon-search"></i> Поиск
          </el-button>
        </el-button-group>
      </el-form-item>
    </el-form>

    <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

    <el-table
        v-loading="loading"
        :data="orders"
        style="width: 100%">
      <el-table-column
          fixed
          label="ID"
          min-width="35">
        <template slot-scope="props">
          {{props.row.id}} <template v-if="!props.row.read_status"><span style="color: red;">NEW!</span></template>
        </template>
      </el-table-column>
      <el-table-column min-width="70">
        <template slot-scope="props">
            <p :style="`background-color: ${getLastStatus(props.row.id).color};color: #fff;text-align: center`">
              {{getLastStatus(props.row.id).name}}
            </p>
        </template>
      </el-table-column>
      <el-table-column
          label="Заказчик"
          min-width="120">
        <template slot-scope="props">
          <template v-if="props.row.user_id">
            <router-link :to="{ name: 'users-update', params: { id: props.row.user_id }}">
              <template v-if="props.row.user_surname !== null && props.row.user_name !== null">
                {{props.row.user_surname}} {{props.row.user_name}} (ID {{props.row.user_id}})
              </template>
              <template v-else>Страница пользователя (ID {{props.row.user_id}})</template>
            </router-link>
            <br>
          </template>
          <template v-else>
            {{props.row.user_surname}}
            {{props.row.user_name}}
            <br>
          </template>
          <template v-if="props.row.email !== null">E-Mail: {{props.row.email}}<br></template>
          <template v-if="props.row.phone !== null">Телефон: {{props.row.phone}}</template>
        </template>
      </el-table-column>
      <el-table-column
          prop="total_price"
          label="Сумма заказа"
          min-width="80">
      </el-table-column>
      <el-table-column
          prop="total_discount_price"
          label="Сумма заказа"
          min-width="100">
      </el-table-column>
      <el-table-column
          fixed="right"
          min-width="70">
        <template slot-scope="props">
          <el-button-group>
            <el-button
                @click.native.prevent="goToUpdate(props.row.id)"
                size="mini">
              <i class="el-icon-edit"></i>
            </el-button>
            <el-button
                size="mini"
                type="danger"
                @click.native.prevent="btnDeleteOrder(props.$index, orders)">
              <i class="el-icon-delete"></i>
            </el-button>
          </el-button-group>
        </template>
      </el-table-column>
    </el-table>

    <PageElementsPagination :total="total"
                            :currentPage="currentPage"
                            :pageSize="pageSize"
                            v-on:change="handleCurrentPageChange"/>

    <el-dialog
        :title="titleDialog"
        :visible.sync="dialogVisible"
        width="30%">
      <el-alert
          :title="titleAlert"
          :type="typeAlert"
          :closable="false">
      </el-alert>
      <span slot="footer" class="dialog-footer">
                <el-button-group>
                    <el-button @click="dialogVisible = false">Отмена</el-button>
                    <el-button type="primary" @click="deleteOrder">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import * as ApiOrders from '../../../app/admin/api/Orders';
  import * as helperRouter from '../../../app/helpers/router';
  import { PageElementsBreadcrumb, PageElementsPagination, PageElementsAlerts } from '../page/elements';

  export default {
    name: 'orders-list',
    mounted () {
      if (this.$route.query.update_model !== undefined) {
        this.goToUpdate(this.$route.query.update_model);
      }

      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'orders-list'}).href, title: this.$router.currentRoute.meta.title},
      ];

      this.formSearch = this.searchOrders;
      this.oldFormSearch = _.cloneDeep(this.formSearch);

      if (typeof this.ordersInStore.data !== 'undefined' && this.ordersInStore.data.length) {
        this.orders = this.ordersInStore.data;
        this.total = this.ordersInStore.total;
        this.currentPage = this.ordersInStore.current_page;
        this.pageSize = this.ordersInStore.per_page;
        this.loading = false;

        return false;
      }

      let page = helperRouter.currentPage(this.$router);
      this.getOrders((page !== undefined) ? page : 1);
    },
    computed: {
      ordersInStore: function() {
        return this.$store.getters.orders;
      },
      selectBoolean: function () {
        return this.$store.getters.selectDataBoolean;
      },
      searchOrders: function () {
        return this.$store.getters.searchOrders;
      },
    },
    data() {
      return {
        orders: [],
        breadcrumbElements: [],
        currentPage: 0,
        total: 0,
        pageSize: 0,
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        operationsOnOrder: null,
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
        formSearch: {
          id: null,
          user_name: '',
          user_surname: '',
          user_id: null,
          only_new: 0,
        },
        oldFormSearch: null
      }
    },
    methods: {
      getLastStatus: function (id) {
        let order = this.orders.find(item => item.id === id);
        if (order !== undefined && order.history_statuses.length === 0) {
          return null;
        }
        else {
          return _.last(order.history_statuses).status;
        }
      },
      deleteOrder: function () {
        if (this.operationsOnOrder) {
          ApiOrders.destroy(this.operationsOnOrder.id).then((response) => {
            let order = this.ordersInStore;
            let index = order.data.findIndex((item) => item.id === this.operationsOnOrder.id);
            order.data.splice(index, 1);

            this.$store.commit('updateOrders', order);
            this.dialogVisible = false;

            if (!this.operationsOnOrder.read_status) {
              this.$store.commit('updateNewOrders', this.$store.getters.newOrders - 1);
            }

            this.operationsOnOrder = null;

            this.$notify.success({
              offset: 50,
              title: 'Удаление заказа',
              message: response.data.message
            });
          }).catch((error) => {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'при удалении заказа'
            });
            this.typeAlert = 'error';
            this.alerts = error.response.data.errors;
            this.dialogVisible = false;
            this.operationsOnOrder = null;
          });
        }
      },
      btnDeleteOrder: function (index, orders) {
        this.operationsOnOrder = orders[index];
        this.titleDialog = 'Удаление заказа';
        this.titleAlert = `Вы дейстительно хотите удалить заказ с ID "${this.operationsOnOrder.id}"?`;
        this.dialogVisible = true;
      },
      getOrders: function (page = 1) {
        this.loading = true;
        return ApiOrders.get(page, this.formSearch).then((response) => {
          this.orders = response.data.orders.data;
          this.total = response.data.orders.total;
          this.currentPage = response.data.orders.current_page;
          this.pageSize = response.data.orders.per_page;
          this.$store.commit('updateOrders', response.data.orders);
          this.loading = false;
        });
      },
      goToUpdate: function (id) {
        this.$router.push({name: 'orders-update', params: {id: id}})
      },
      handleCurrentPageChange: function (page) {
        this.getOrders(page);
      },
      onResetSearch: function () {
        this.formSearch = {
          id: null,
          user_name: '',
          user_surname: '',
          user_id: null,
          only_new: 0,
        };
        this.oldFormSearch = this.formSearch;
        this.$store.commit('updateSearchOrders', this.formSearch);

        this.getOrders(1);
      },
      onSubmitSearch: function () {
        this.getOrders(1);
        this.oldFormSearch = this.formSearch;
        this.$store.commit('updateSearchOrders', this.formSearch);
      },
    },
    components: {
      PageElementsBreadcrumb,
      PageElementsAlerts,
      PageElementsPagination
    }
  }
</script>