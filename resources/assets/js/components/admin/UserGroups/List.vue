<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

    <el-table
        v-loading="loading"
        :data="user_groups"
        style="width: 100%">
      <el-table-column
          fixed
          prop="id"
          label="ID"
          min-width="50">
      </el-table-column>
      <el-table-column
          prop="name"
          label="Наименование"
          min-width="150">
      </el-table-column>
      <el-table-column
          prop="discount"
          label="Процент скидки на группу"
          min-width="150">
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
                @click.native.prevent="btnDeleteUserGroups(props.$index, user_groups)">
              <i class="el-icon-delete"></i>
            </el-button>
          </el-button-group>
        </template>
      </el-table-column>
    </el-table>

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
                    <el-button type="primary" @click="deleteUserGroup">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import * as ApiUserGroups from '../../../app/admin/api/UserGroups';
  import * as helperRouter from '../../../app/helpers/router';
  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

  export default {
    name: 'types-list',
    mounted () {
      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'user-groups-list'}).href, title: this.$router.currentRoute.meta.title},
      ];

      if (this.userGroupsInStore.length) {
        this.user_groups = this.userGroupsInStore;
        this.loading = false;
        return false;
      }
      this.getUserGroups();
    },
    computed: {
      userGroupsInStore: function() {
        return this.$store.getters.userGroups;
      }
    },
    data() {
      return {
        user_groups: [],
        breadcrumbElements: [],
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        operationsOnUserGroups: null,
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
      }
    },
    methods: {
      deleteUserGroup: function () {
        if (this.operationsOnUserGroups) {
          ApiUserGroups.destroy(this.operationsOnUserGroups.id).then((response) => {
            let user_groups = this.userGroupsInStore;
            let index = user_groups.findIndex((user_group) => user_group.id === this.operationsOnUserGroups.id);
            user_groups.splice(index, 1);
            this.$store.commit('updateUserGroups', user_groups);
            this.dialogVisible = false;
            this.operationsOnUserGroups = null;
            this.$notify.success({
              offset: 50,
              title: 'Удаление группы пользователей',
              message: response.data.message
            });
          }).catch((error) => {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'при удалении группы пользователей'
            });
            this.typeAlerts = 'error';
            this.alerts = error.response.data.errors;
            this.dialogVisible = false;
            this.operationsOnUserGroups = null;
          });
        }
      },
      btnDeleteUserGroups: function (index, group) {
        this.operationsOnUserGroups = group[index];
        this.titleDialog = 'Удаление группы пользователей';
        this.titleAlert = `Вы дейстительно хотите удалить группу пользователей ${this.operationsOnUserGroups.name}? Группа удаляется, а сами пользователи которые были в этой группе остаются.`;
        this.dialogVisible = true;
      },
      getUserGroups: function () {
        this.loading = true;
        return ApiUserGroups.get().then((response) => {
          this.user_groups = response.data.user_groups;
          this.$store.commit('updateUserGroups', response.data.user_groups);
          this.loading = false;
        });
      },
      goToUpdate: function (id) {
        this.$router.push({name: 'user-groups-update', params: {id: id}})
      }
    },
    components: {
      PageElementsBreadcrumb,
      PageElementsAlerts
    }
  }
</script>