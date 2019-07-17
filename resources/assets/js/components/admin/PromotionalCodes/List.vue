<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

    <el-form :inline="true" :model="formSearch"
             @keyup.enter.native="onSubmitSearch"
             class="ds-query-form">
      <el-form-item label="Текст запроса">
        <el-input v-model="formSearch.q" placeholder="Введите текст запроса"></el-input>
      </el-form-item>
      <el-form-item label="Статус пользователя" prop="status">
        <el-select v-model="formSearch.status" placeholder="Выберите статус кода">
          <el-option
              v-for="item in listStatuses"
              :key="item.value"
              :label="item.label"
              :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmitSearch">
          <i class="el-icon-search"></i> Поиск
        </el-button>
      </el-form-item>
    </el-form>

    <el-table
        v-loading="loading"
        :data="promotionalCodes"
        style="width: 100%">
      <el-table-column
          fixed
          prop="id"
          label="ID"
          min-width="50">
      </el-table-column>
      <el-table-column
          prop="code"
          label="Код"
          min-width="150">
      </el-table-column>
      <el-table-column type="expand">
        <template slot-scope="props">
          <p>Дата создания: {{ props.row.created_at }}</p>
        </template>
      </el-table-column>
      <el-table-column
          label=""
          min-width="150">
        <template slot-scope="props">
          <template v-if="props.row.type === 0">
            <p>Тип: Скидка</p>
          </template>
          <template v-else>
            <p>Тип: Денежный</p>
          </template>

          <template v-if="props.row.type === 0">
            <p>Значение: {{props.row.discount}}</p>
          </template>
          <template v-else>
            <p>Значение: {{props.row.cash_value}}</p>
          </template>
        </template>
      </el-table-column>
      <el-table-column
          label="Статус"
          min-width="150">
        <template slot-scope="props">
          <template v-if="props.row.status">
            <p>Активный</p>
          </template>
          <template v-else>
            <p>Был использован</p>
          </template>
        </template>
      </el-table-column>
      <el-table-column
          fixed="right"
          min-width="150">
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
                @click.native.prevent="btnDeletePromotionCode(props.$index, promotionalCodes)">
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
                    <el-button type="primary" @click="deletePromotionalCode">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>

  </div>
</template>

<script>
  import * as ApiPromotionalCodes from '../../../app/admin/api/PromotionalCodes';
  import * as helperRouter from '../../../app/helpers/router';

  import { PageElementsPagination, PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

  export default {
    name: 'promotional-codes-list',
    mounted () {
      this.formSearch = this.searchData;
      this.oldFormSearch = _.cloneDeep(this.formSearch);

      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'promotional-codes-list'}).href, title: this.$router.currentRoute.meta.title},
      ];
      if (this.promotionalCodesStore.data !== undefined
        && this.promotionalCodesStore.data.length) {
        this.promotionalCodes = this.promotionalCodesStore.data;
        this.total = this.promotionalCodesStore.total;
        this.currentPage = this.promotionalCodesStore.current_page;
        this.pageSize = this.promotionalCodesStore.per_page;

        helperRouter.updatePage(this.$router, this.currentPage);

        this.loading = false;

        return false;
      }

      let page = helperRouter.currentPage(this.$router);
      this.getPromotionalCodes((page !== undefined) ? page : 1);
    },
    data() {
      return {
        promotionalCodes: [],
        currentPage: 0,
        total: 0,
        pageSize: 0,
        breadcrumbElements: [],
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        operationsOnPromotionalCode: null,
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
        formSearch: {
          q: '',
          status: 'all'
        },
        oldFormSearch: null,
        statuses: this.selectListPromotionalCodeStatuses
      }
    },
    computed: {
      searchData: function () {
        return this.$store.getters.searchPromotionalCodes;
      },
      listStatuses: function () {
        let statuses = this.selectListPromotionalCodeStatuses;
        if (statuses.findIndex((item) => item.value === 'all') === -1) {
          statuses.unshift({'label': 'Все Промо-коды', 'value': 'all'});
        }
        return statuses;
      },
      promotionalCodesStore: function () {
        return this.$store.getters.promotionalCodes;
      },
      selectListPromotionalCodeStatuses: function () {
        return this.$store.getters.selectDataListPromotionalCodeStatuses;
      },
      orders: function () {
        return this.$store.getters.orders;
      }
    },
    methods: {
      onSubmitSearch: function () {
        this.getPromotionalCodes();
        this.oldFormSearch = this.formSearch;
        this.$store.commit('updateSearchPromotionalCodes', this.formSearch);
      },
      deletePromotionalCode: function () {
        if (this.operationsOnPromotionalCode) {
          ApiPromotionalCodes.destroy(this.operationsOnPromotionalCode.id).then((response) => {
            let promotionalCodes = this.promotionalCodesStore;
            let index = promotionalCodes.data.findIndex((code) => code.id === this.operationsOnPromotionalCode.id);
            promotionalCodes.data.splice(index, 1);
            this.$store.commit('updatePromotionalCodes', promotionalCodes);

            if (this.orders.data !== undefined) {
              let orders = this.orders;
              let index = orders.data.findIndex((item) => item.promotional_code_id === this.operationsOnPromotionalCode.id);
              if (index !== -1 && response.data.order !== null && orders.data[index].id === response.data.order.id) {
                orders.data[index] = response.data.order;

                this.$store.commit('updateOrders', orders);
              }
            }

            this.dialogVisible = false;
            this.operationsOnPromotionalCode = null;
            this.$notify.success({
              offset: 50,
              title: 'Удаление промокода',
              message: response.data.message
            });
          }).catch((error) => {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'при удалении промокода'
            });
            this.typeAlerts = 'error';
            this.alerts = error.response.data.errors;
            this.dialogVisible = false;
            this.operationsOnPromotionalCode = null;
          });
        }
      },
      btnDeletePromotionCode: function (index, promotionalCodes) {
        this.operationsOnPromotionalCode = promotionalCodes[index];
        this.titleDialog = 'Удаление промокода';
        this.titleAlert = `Вы дейстительно хотите удалить Промо-код: ${this.operationsOnPromotionalCode.code}?`;
        this.dialogVisible = true;
      },
      getPromotionalCodes: function (page = 1) {
        this.loading = true;
        return ApiPromotionalCodes.get(page, this.formSearch).then((response) => {
          this.promotionalCodes = response.data.promotional_codes.data;
          this.total = response.data.promotional_codes.total;
          this.currentPage = response.data.promotional_codes.current_page;
          this.pageSize = response.data.promotional_codes.per_page;
          this.$store.commit('updatePromotionalCodes', response.data.promotional_codes);

          helperRouter.updatePage(this.$router, this.currentPage);

          this.loading = false;
        });
      },
      handleCurrentPageChange: function (page) {
        this.getPromotionalCodes(page);
      },
      goToUpdate: function (id) {
        this.$router.push({name: 'promotional-codes-update', params: {id: id}})
      }
    },
    components: {
      PageElementsPagination,
      PageElementsBreadcrumb,
      PageElementsAlerts
    },
    beforeDestroy() {
      this.$store.commit('updateSearchPromotionalCodes', this.oldFormSearch);
    }
  }
</script>