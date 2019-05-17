<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

    <el-table
        v-loading="loading"
        :data="sliders"
        style="width: 100%">
      <el-table-column
          fixed
          prop="id"
          label="ID"
          width="50">
      </el-table-column>
      <el-table-column
          fixed
          label="Изображение"
          width="120">
        <template slot-scope="props" v-if="props.row.image_preview !== null">
          <img width="70px" height="auto" :src="'/app/public/images/slider/'+ props.row.image_preview">
        </template>
      </el-table-column>
      <el-table-column
          prop="title"
          width="200px"
          label="Заголовок">
      </el-table-column>
      <el-table-column
          prop="sorting_order"
          label="Порядок сорт."
          min-width="50">
      </el-table-column>
      <el-table-column
          fixed="right">
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
                @click.native.prevent="btnDeleteSlider(props.$index, sliders)">
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
                    <el-button type="primary" @click="deleteSlider">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import * as helperRouter from '../../../app/helpers/router';
  import * as ApiSliders from '../../../app/admin/api/Sliders';

  import { PageElementsPagination, PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

  export default {
    name: 'products-list',
    mounted () {
      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'sliders-list'}).href, title: this.$router.currentRoute.meta.title},
      ];
      if (typeof this.slidersStore.data !== 'undefined'
        && this.slidersStore.data.length) {
        this.sliders = this.slidersStore.data;
        this.total = this.slidersStore.total;
        this.currentPage = this.slidersStore.current_page;
        this.pageSize = this.slidersStore.per_page;

        helperRouter.updatePage(this.$router, this.currentPage);

        this.loading = false;

        return true;
      }

      let page = helperRouter.currentPage(this.$router);
      this.getSliders((page !== undefined) ? page : 1);
    },
    data() {
      return {
        sliders: [],
        currentPage: 0,
        total: 0,
        pageSize: 0,
        breadcrumbElements: [],
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        operationsOnSlider: null,
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
      }
    },
    computed: {
      slidersStore: function () {
        return this.$store.getters.sliders;
      }
    },
    methods: {
      deleteSlider: function () {
        if (this.operationsOnSlider) {
          ApiSliders.destroy(this.operationsOnSlider.id).then((response) => {
            let sliders = this.slidersStore;
            let index = sliders.data.findIndex((item) => item.id === this.operationsOnSlider.id);
            sliders.data.splice(index, 1);
            this.$store.commit('updateSliders', sliders);
            this.dialogVisible = false;
            this.operationsOnSlider = null;
            this.$notify.success({
              offset: 50,
              title: 'Удаление слайда',
              message: response.data.message
            });
          }).catch((error) => {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'при удалении слайда'
            });
            this.typeAlerts = 'error';
            this.alerts = error.response.data.errors;
            this.dialogVisible = false;
            this.operationsOnSlider = null;
          });
        }
      },
      btnDeleteSlider: function (index, sliders) {
        this.operationsOnSlider = sliders[index];
        this.titleDialog = 'Удаление слайда';
        this.titleAlert = `Вы дейстительно хотите удалить слайд: ${this.operationsOnSlider.title} (ID: ${this.operationsOnSlider.id})?`;
        this.dialogVisible = true;
      },
      getSliders: function (page = 1) {
        this.loading = true;
        return ApiSliders.get(page).then((response) => {
          this.sliders = response.data.sliders.data;
          this.total = response.data.sliders.total;
          this.currentPage = response.data.sliders.current_page;
          this.pageSize = response.data.sliders.per_page;
          this.$store.commit('updateSliders', response.data.sliders);

          helperRouter.updatePage(this.$router, this.currentPage);

          this.loading = false;
        });
      },
      handleCurrentPageChange: function (page) {
        this.getSliders(page);
      },
      goToUpdate: function (id) {
        this.$router.push({name: 'sliders-update', params: {id: id}})
      }
    },
    components: {
      PageElementsPagination,
      PageElementsBreadcrumb,
      PageElementsAlerts
    },
  }
</script>