<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

    <el-form :inline="true" :model="formSearch" class="ds-query-form">
      <el-form-item label="Текст запроса">
        <el-input v-model="formSearch.q" placeholder="Введите текст запроса"></el-input>
      </el-form-item>
      <el-form-item label="Статус пользователя" prop="status">
        <el-select v-model="formSearch.status" placeholder="Выберите статус пользователя">
          <el-option
              v-for="item in this.listStatuses"
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
        :data="users"
        style="width: 100%">
      <el-table-column
          fixed
          prop="id"
          label="ID"
          min-width="50">
      </el-table-column>
      <el-table-column
          prop="user_surname"
          label="Фимилия"
          min-width="100">
      </el-table-column>
      <el-table-column
          prop="user_name"
          label="Имя"
          min-width="100">
      </el-table-column>
      <el-table-column type="expand">
        <template slot-scope="props">
          <p>E-mail: {{ props.row.email }}</p>
          <template v-if="props.row.reliability === 1">
            <p>Статус надежности: Надежный пользователь</p>
          </template>
          <template v-else>
            <p>Статус надежности: Не надежный пользователь</p>
          </template>
          <p v-if="props.row.description !== null && props.row.description.length > 0">Примечание: {{ props.row.description }}</p>
          <p v-if="props.row.discount !== 0">Персональный процент скидки: {{ props.row.discount}}</p>
        </template>
      </el-table-column>
      <el-table-column
          prop="email"
          label="E-mail"
          min-width="150">
      </el-table-column>
      <el-table-column
          label="Статус"
          min-width="150">
        <template slot-scope="props">
          <template v-if="props.row.status === 'administration'">
            <p>Администратор</p>
          </template>
          <template v-else>
            <p>Пользователь</p>
          </template>
        </template>
      </el-table-column>
      <el-table-column
          fixed="right"
          min-width="100">
        <template slot-scope="props">
          <el-button-group>
            <el-button @click.native.prevent="userOrders(props.row.id)"
                       type="primary"
                       size="mini">
              <i class="el-icon-goods"></i>
            </el-button>
            <el-button @click.native.prevent="goToUpdate(props.row.id)"
                       size="mini">
              <i class="el-icon-edit"></i>
            </el-button>
            <el-button size="mini"
                       type="danger"
                       @click.native.prevent="btnDeleteUser(props.$index, users)">
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
                    <el-button type="primary" @click="deleteUser">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import * as ApiUsers from '../../../app/admin/api/Users';
  import * as helperRouter from '../../../app/helpers/router';

  import { PageElementsPagination, PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

  export default {
    name: 'users-list',
    mounted () {
      this.formSearch = this.searchData;
      this.oldFormSearch = _.cloneDeep(this.formSearch);

      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'users-list'}).href, title: this.$router.currentRoute.meta.title},
      ];
      if (this.usersStore.data !== undefined
        && this.usersStore.data.length) {
        this.users = this.usersStore.data;
        this.total = this.usersStore.total;
        this.currentPage = this.usersStore.current_page;
        this.pageSize = this.usersStore.per_page;

        helperRouter.updatePage(this.$router, this.currentPage);

        this.loading = false;

        return false;
      }

      let page = helperRouter.currentPage(this.$router);
      this.getUsers((page !== undefined) ? page : 1);
    },
    data() {
      return {
        users: [],
        currentPage: 0,
        total: 0,
        pageSize: 0,
        breadcrumbElements: [],
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        operationsOnUser: null,
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
        formSearch: {
          q: '',
          status: 'all'
        },
        oldFormSearch: null,
        statuses: this.selectListStatuses
      }
    },
    computed: {
      selectListStatuses: function () {
        return this.$store.getters.selectDataListStatuses;
      },
      searchData: function () {
        return this.$store.getters.searchUsers
      },
      listStatuses: function () {
        let statuses = this.selectListStatuses;
        if (statuses.findIndex((item) => item.value === 'all') === -1) {
          statuses.unshift({'label': 'Все пользователи', 'value': 'all'});
        }
        return statuses;
      },
      usersStore: function () {
        return this.$store.getters.users;
      }
    },
    methods: {
      userOrders: function (userId) {
        let formSearch = this.$store.getters.searchOrders;
        formSearch.user_id = userId;
        this.$store.commit('updateSearchOrders', formSearch);
        this.$router.push({name: 'orders-list'});
      },
      onSubmitSearch: function () {
        this.getUsers();
        this.oldFormSearch = this.formSearch;
        this.$store.commit('updateSearchUsers', this.formSearch);
      },
      deleteUser: function () {
        if (this.operationsOnUser) {
          ApiUsers.destroy(this.operationsOnUser.id).then((response) => {
            let users = this.usersStore;
            let index = users.data.findIndex((user) => user.id === this.operationsOnUser.id);
            users.data.splice(index, 1);
            this.$store.commit('updateUsers', users);
            this.dialogVisible = false;
            this.operationsOnUser = null;
            this.$notify.success({
              offset: 50,
              title: 'Удаление пользователя',
              message: response.data.message
            });
          }).catch((error) => {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'при удалении пользователя'
            });
            this.typeAlerts = 'error';
            this.alerts = error.response.data.errors;
            this.dialogVisible = false;
            this.operationsOnUser = null;
          });
        }
      },
      btnDeleteUser: function (index, users) {
        this.operationsOnUser = users[index];
        this.titleDialog = 'Удаление пользователя';
        this.titleAlert = `Вы дейстительно хотите удалить пользователя ${this.operationsOnUser.name}?`;
        this.dialogVisible = true;
      },
      getUsers: function (page = 1) {
        this.loading = true;
        return ApiUsers.get(page, this.formSearch).then((response) => {
          this.users = response.data.users.data;
          this.total = response.data.users.total;
          this.currentPage = response.data.users.current_page;
          this.pageSize = response.data.users.per_page;
          this.$store.commit('updateUsers', response.data.users);

          helperRouter.updatePage(this.$router, this.currentPage);

          this.loading = false;
        });
      },
      handleCurrentPageChange: function (page) {
        this.getUsers(page);
      },
      goToUpdate: function (id) {
        this.$router.push({name: 'users-update', params: {id: id}})
      }
    },
    components: {
      PageElementsPagination,
      PageElementsBreadcrumb,
      PageElementsAlerts
    },
    beforeDestroy() {
      this.$store.commit('updateSearchUsers', this.oldFormSearch);
    }
  }
</script>