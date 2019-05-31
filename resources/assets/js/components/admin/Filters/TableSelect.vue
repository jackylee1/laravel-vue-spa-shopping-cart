<template>
  <div>
    <el-table
        v-loading="loading"
        :data="filters"
        style="width: 100%">
      <el-table-column
          fixed
          prop="id"
          label="ID"
          min-width="50">
      </el-table-column>
      <el-table-column
          prop="name"
          label="Имя"
          min-width="150">
      </el-table-column>
      <el-table-column
          prop="slug"
          label="SEO"
          min-width="150">
      </el-table-column>
      <el-table-column
          fixed="right"
          label="Операции"
          min-width="70">
        <template slot-scope="props">
          <el-button
              :type="btnSelect(props.row.id) ? 'success' : 'danger'"
              :icon="btnSelect(props.row.id) ? 'el-icon-check' : 'el-icon-close'" circle
              @click="selectFilter(props.row)">
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
  import * as ApiFilters from '../../../app/admin/api/Filters';

  import { PageElementsAlerts } from '../page/elements';

  export default {
    name: 'filters-table-select',
    props: ['currentModel', 'model'],
    mounted () {
      if (this.filtersStore !== undefined
        && this.filtersStore.length) {
        this.filters = this.selectParents(this.filtersStore);
        this.loading = false;
        return false;
      }
      this.getFilters();
    },
    data() {
      return {
        filters: [],
        loading: true,
      }
    },
    computed: {
      filtersStore: function () {
        return this.$store.getters.filters;
      }
    },
    methods: {
      selectParents: function(filters) {
        return filters.filter((item) => {
          return item.parent_id === 0;
        })
      },
      selectFilter: function (filter) {
        this.$emit('selectFilter', filter);
      },
      getFilters: function () {
        this.loading = true;
        return ApiFilters.get().then((response) => {
          this.filters = this.selectParents(response.data.filters);
          this.$store.commit('updateFilters', response.data.filters);
          this.loading = false;
        });
      },
      btnSelect: function(filterId) {
        let check = null;
        switch (this.currentModel) {
          case 'types':
          case 'type_category':
            check = this.model.filters.find((filter) => filter.filter_id === filterId);
            break;
        }
        return (check !== null && check !== undefined);
      },
    },
    components: {
      PageElementsAlerts
    }
  }
</script>

