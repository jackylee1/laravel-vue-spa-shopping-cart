<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

    <el-table
        v-loading="loading"
        :data="types"
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
          prop="slug"
          label="SEO URL"
          min-width="150">
      </el-table-column>
      <el-table-column
          prop="sorting_order"
          label="Порядок сорт."
          min-width="150">
      </el-table-column>
      <el-table-column
          fixed="right"
          min-width="150">
        <template slot-scope="props">
          <el-button-group>
            <el-tooltip content="Скопировать ссылку" placement="top">
              <el-button size="mini"
                         @click="clipboardCopyUrl(props.row.slug)">
                <i class="ai-copy"></i>
              </el-button>
            </el-tooltip>
            <el-button size="mini"
                       @click.native.prevent="viewOnSite(props.row)">
              <i class="el-icon-view"></i>
            </el-button>
            <el-button
                @click.native.prevent="goToUpdate(props.row.id)"
                size="mini">
              <i class="el-icon-edit"></i>
            </el-button>
            <el-button
                size="mini"
                type="danger"
                @click.native.prevent="btnDeleteType(props.$index, types)">
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
                    <el-button type="primary" @click="deleteType">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';
  import * as ApiTypes from '../../../app/admin/api/Types';
  import * as helperRouter from '../../../app/helpers/router';

  export default {
    name: 'types-list',
    mounted () {
      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'types-list'}).href, title: this.$router.currentRoute.meta.title},
      ];

      if (this.typesInStore.length) {
        this.types = this.typesInStore;
        this.loading = false;

        return false;
      }
      this.getTypes();
    },
    computed: {
      typesInStore: function() {
        return this.$store.getters.types;
      }
    },
    data() {
      return {
        types: [],
        breadcrumbElements: [],
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        operationsOnType: null,
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
      }
    },
    methods: {
      viewOnSite: function (item) {
        window.open(location.protocol+'//'+location.hostname+'/catalog?type='+item.slug, '_blank');
      },
      deleteType: function () {
        if (this.operationsOnType) {
          ApiTypes.destroy(this.operationsOnType.id).then((response) => {
            let types = this.typesInStore;
            let index = types.findIndex((type) => type.id === this.operationsOnType.id);
            types.splice(index, 1);
            this.$store.commit('updateTypes', types);
            this.dialogVisible = false;
            this.operationsOnType = null;
            this.$notify.success({
              offset: 50,
              title: 'Удаление типа товара',
              message: response.data.message
            });
          }).catch((error) => {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'при удалении типа товара'
            });
            this.typeAlerts = 'error';
            this.alerts = error.response.data.errors;
            this.dialogVisible = false;
            this.operationsOnType = null;
          });
        }
      },
      btnDeleteType: function (index, users) {
        this.operationsOnType = users[index];
        this.titleDialog = 'Удаление тип товара';
        this.titleAlert = `Вы дейстительно хотите удалить тип товара ${this.operationsOnType.name}?`;
        this.dialogVisible = true;
      },
      getTypes: function () {
        this.loading = true;
        return ApiTypes.get().then((response) => {
          this.types = response.data.types;
          this.$store.commit('updateTypes', response.data.types);
          this.loading = false;
        });
      },
      goToUpdate: function (id) {
        this.$router.push({name: 'types-update', params: {id: id}})
      },
      clipboardCopyUrl: function (slug) {
        let url = location.protocol+'//'+location.hostname;
        url += `/catalog?type=${slug}`;
        this.$copyText(url).then(() => {
          this.$notify.success({
            offset: 50,
            title: 'Ссылка скопирована в буфер обмена'
          });
        }).catch(() => {
          this.$notify.error({
            offset: 50,
            title: 'Ошибка при копировании ссылки в буфер обмен'
          });
        });
      }
    },
    components: {
      PageElementsBreadcrumb,
      PageElementsAlerts
    }
  }
</script>