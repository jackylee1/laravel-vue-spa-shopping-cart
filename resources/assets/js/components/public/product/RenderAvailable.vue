<template>
  <div>
    <template v-if="this.filters !== null && this.filters.length > 0">
      <template v-for="(available, index) in _.filter(availableModels, (item) => item.quantity > 0
              && _.filter(item.filters, (filter) => {
                  let temp = getFilter(getFilter(filter.filter_id).parent_id);
                  return temp !== null && temp.active;
               }).length)">
        <label @click="changeIdAvailable(available.id)"
               :class="(index === 0) ? 'btn btn-primary active' : 'btn btn-primary'">
          <input type="radio"
                 v-model="idAvailable"
                 :value="available.id"
                 :id="'option'+(index+1)"
                 checked>
          <template v-for="(filter, indexFilter) in available.filters">
            {{ getFilter(filter.filter_id).name }}
            <template v-if="indexFilter !== available.filters.length - 1">
              +
            </template>
          </template>
        </label>
      </template>
    </template>
  </div>
</template>

<script>
  export default {
    name: 'RenderAvailable',
    props: ['availableModels', 'idAvailable'],
    computed: {
      _() {
        return _;
      },
      filters: function () {
        return this.$store.getters.filters;
      }
    },
    mounted() {
      if (this.availableModels.length > 0) {
        let available = _.find(this.availableModels, function (x) { return x.quantity > 0 && x.filters.length });
        if (available !== null && available !== undefined) {
          this.$emit('changeIdAvailable', available.id);
        }
      }
    },
    methods: {
      changeIdAvailable: function (id) {
        this.$emit('changeIdAvailable', id);
      },
      getFilter: function (id) {
        let filter = this.filters.find(item => item.id === id);
        if (filter !== undefined) {
          return filter;
        }
        return null;
      },
    }
  }
</script>