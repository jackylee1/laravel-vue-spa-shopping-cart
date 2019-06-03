<template>
  <div>
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
          min-width="25">
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
          label="Операции"
          min-width="70">
        <template slot-scope="props">
          <el-button
              :type="(relationForAction != null
                                    && relationKey != null
                                    && props.row[relationForAction] !== null
                                    && props.row[relationForAction][relationKey] !== null
                                    && props.row[relationForAction][relationKey] === parseInt($route.params.id))
                                    ? 'success' : 'danger'"
              :icon="(relationForAction != null
                                    && relationKey != null
                                    && props.row[relationForAction] !== null
                                    && props.row[relationForAction][relationKey] !== null
                                    && props.row[relationForAction][relationKey] === parseInt($route.params.id))
                                    ? 'el-icon-check' : 'el-icon-close'" circle
              @click="selectUser(props.row)">
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <PageElementsPagination :total="total"
                            :currentPage="currentPage"
                            :pageSize="pageSize"
                            v-on:change="handleCurrentPageChange"/>
  </div>
</template>

<script>
  import * as ApiUsers from '../../../app/admin/api/Users';

  import { PageElementsPagination, PageElementsAlerts } from '../page/elements';

  export default {
    name: 'users-table-select',
    props: ['relationForAction', 'relationKey'],
    mounted () {
      this.formSearch = this.searchData;
      this.oldFormSearch = _.cloneDeep(this.formSearch);

      if (typeof this.getUsersStorage.data !== 'undefined' && this.getUsersStorage.data.length) {
        this.users = this.getUsersStorage.data;
        this.total = this.getUsersStorage.total;
        this.currentPage = this.getUsersStorage.current_page;
        this.pageSize = this.getUsersStorage.per_page;
        this.loading = false;
        return false;
      }
      this.getUsers();
    },
    computed: {
      searchData: function () {
        return this.$store.getters.searchUsers
      },
      selectListStatuses: function () {
        return this.$store.getters.selectDataListStatuses;
      },
      listStatuses: function () {
        let statuses = this.selectListStatuses;
        let index = statuses.findIndex(item => item.value === 'all');
        if (index === -1) {
          statuses.unshift({'label': 'Все пользователи', 'value': 'all'});
        }

        return statuses;
      },
      getUsersStorage: function () {
        return this.$store.getters.users;
      },
    },
    data() {
      return {
        users: [],
        currentPage: 1,
        total: 0,
        pageSize: 0,
        loading: true,
        formSearch: {
          q: '',
          status: 'all'
        },
        oldFormSearch: null,
        statuses: this.selectListStatuses
      }
    },
    methods: {
      selectUser: function (user) {
        this.$emit('selectUser', user);
      },
      onSubmitSearch: function () {
        this.getUsers();
        this.oldFormSearch = this.formSearch;
        this.$store.commit('updateSearchUsers', this.formSearch);
      },

      getUsers: function (page = 1) {
        this.loading = true;
        return ApiUsers.get(page, this.formSearch).then((response) => {
          this.users = response.data.users.data;
          this.total = response.data.users.total;
          this.currentPage = response.data.users.current_page;
          this.pageSize = response.data.users.per_page;
          this.$store.commit('updateUsers', response.data.users);
          this.loading = false;
        });
      },
      handleCurrentPageChange: function (page) {
        this.getUsers(page);
      }
    },
    components: {
      PageElementsPagination,
      PageElementsAlerts
    },
    beforeDestroy() {
      this.$store.commit('updateSearchUsers', this.oldFormSearch);
    }
  }
</script>

