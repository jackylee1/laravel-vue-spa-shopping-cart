<template>
  <div class="row description_text">
    <div :class="(product.size_table !== null) ? 'col-md-7' : 'col-md-12'">
      <p v-html="product.description"></p>
    </div>
    <div v-if="sizeTable !== null" class="col-md-5 size_table" id="size_table">
      <h5>Таблица размеров</h5>
      <div v-html="sizeTable.description"></div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Description',
    props: ['product'],
    computed: {
      sizeTables: function () {
        return this.$store.getters.sizeTables;
      }
    },
    mounted() {
      if (this.sizeTables.length) {
        this.setSizeTable();
      }
    },
    data() {
      return {
        sizeTable: null
      }
    },
    methods: {
      setSizeTable: function () {
        if (this.product.size_table !== null) {
          this.sizeTable = this.sizeTables.find((item) => item.id === this.product.size_table.size_table_id);
        }
      }
    },
    watch: {
      'sizeTables': function () {
        this.setSizeTable();
      }
    }
  }
</script>