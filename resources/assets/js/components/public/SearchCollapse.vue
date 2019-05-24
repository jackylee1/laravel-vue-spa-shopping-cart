<template>
  <div class="collapse searchcollapse" id="collapseSubmenu">
    <div class="card card-body">
      <form class="form-inline d-flex justify-content-center"
            action="javascript:void(0)" v-on:keyup.enter="getProducts">
        <input v-model="textSearch"
               name="mobileAutocomplete"
               id="mobileAutocomplete"
               class="form-control"
               type="search"
               placeholder="Введите поисковое слово"
               aria-label="Поиск">
        <button @click="getProducts"
                class="btn">Поиск</button>
      </form>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'SearchCollapse',
    mounted() {
      this.textSearch = this.searchByText;
    },
    computed: {
      searchByText: function () {
        return this.$store.getters.searchByText;
      },
    },
    data() {
      return {
        textSearch: null,
      }
    },
    methods: {
      getProducts: function () {
        this.$store.commit('updateSearchByText', this.textSearch);
        this.$router.push({ query: Object.assign({}, this.$route.query, { text: this.textSearch }) });

        this.$emit('getProducts');
      },
    },
    watch: {
      'searchByText': function (value) {
        this.textSearch = value;
      },
    }
  }
</script>