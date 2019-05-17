<template>
  <div>
    <PageElementsBreadcrumb :breadcrumbElements="breadcrumbElements"/>

    <PageElementsAlerts :alerts="alerts" :type="typeAlert"/>

    <el-table
        v-loading="loading"
        :data="indexMediaFiles"
        style="width: 100%">
      <el-table-column
          fixed
          prop="id"
          label="ID"
          width="50">
      </el-table-column>
      <el-table-column width="120" label="Превью">
        <template slot-scope="props">
          <img width="90" height="auto" :src="`https://i.ytimg.com/vi/${getVideoId(props.row.video)}/default.jpg`">
        </template>
      </el-table-column>
      <el-table-column
          prop="video"
          width="500"
          label="Ссылка на Youtube видео">
      </el-table-column>
      <el-table-column
          width="200"
          prop="sorting_order"
          label="Порядок сорт.">
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
                @click.native.prevent="btnDeleteModel(props.$index, indexMediaFiles)">
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
                    <el-button type="primary" @click="deleteModel">Подтверждаю</el-button>
                </el-button-group>
            </span>
    </el-dialog>
  </div>
</template>

<script>
  import * as helperRouter from '../../../app/helpers/router';
  import * as ApiIndexMediaFiles from '../../../app/admin/api/IndexMediaFiles';

  import { PageElementsBreadcrumb, PageElementsAlerts } from '../page/elements';

  export default {
    name: 'index-media-files-list',
    mounted () {
      this.breadcrumbElements = [
        {href: this.$router.resolve({name: 'main'}).href, title: helperRouter.getRouteByName(this.$router, 'main').meta.title},
        {href: this.$router.resolve({name: 'index-media-files-list'}).href, title: this.$router.currentRoute.meta.title},
      ];
      if (this.indexMediaFilesStore.length) {
        this.indexMediaFiles = this.indexMediaFilesStore;

        this.loading = false;

        return true;
      }
      this.getTextBlockTitles();
    },
    data() {
      return {
        indexMediaFiles: [],
        breadcrumbElements: [],
        loading: true,
        dialogVisible: false,
        titleDialog: '',
        operationsOnModel: null,
        typeAlert: 'warning',
        titleAlert: '',
        alerts: [],
      }
    },
    computed: {
      indexMediaFilesStore: function () {
        return this.$store.getters.indexMediaFiles;
      }
    },
    methods: {
      getVideoId: function (url) {
        return this.$youtube.getIdFromUrl(url);
      },
      deleteModel: function () {
        if (this.operationsOnModel) {
          ApiIndexMediaFiles.destroy(this.operationsOnModel.id).then((response) => {
            let indexMediaFiles = this.indexMediaFilesStore;
            let index = indexMediaFiles.findIndex((item) => item.id === this.operationsOnModel.id);
            indexMediaFiles.splice(index, 1);
            this.$store.commit('updateIndexMediaFiles', indexMediaFiles);
            this.dialogVisible = false;
            this.operationsOnModel = null;
            this.$notify.success({
              offset: 50,
              title: 'Удаление записи',
              message: response.data.message
            });
          }).catch((error) => {
            this.$notify.error({
              offset: 50,
              title: 'Ошибка',
              message: 'при удалении записи'
            });
            this.typeAlerts = 'error';
            this.alerts = error.response.data.errors;
            this.dialogVisible = false;
            this.operationsOnModel = null;
          });
        }
      },
      btnDeleteModel: function (index, sliders) {
        this.operationsOnModel = sliders[index];
        this.titleDialog = 'Удаление записи';
        this.titleAlert = `Вы дейстительно хотите удалить запись: ${this.operationsOnModel.video} (ID: ${this.operationsOnModel.id})?`;
        this.dialogVisible = true;
      },
      getTextBlockTitles: function () {
        this.loading = true;
        return ApiIndexMediaFiles.get().then((response) => {
          this.indexMediaFiles = response.data.index_media_files;
          this.$store.commit('updateIndexMediaFiles', this.indexMediaFiles);

          this.loading = false;
        });
      },
      goToUpdate: function (id) {
        this.$router.push({name: 'index-media-files-update', params: {id: id}})
      }
    },
    components: {
      PageElementsBreadcrumb,
      PageElementsAlerts
    },
  }
</script>