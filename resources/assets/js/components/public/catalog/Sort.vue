<template>
  <div>
    <div class="row titles_top">
      <div class="col-lg-5 col-md-3 col-12 my-auto">
        <template v-if="currentCategory !== null">
          <h1 class="category_title">{{currentCategory.name}}</h1>
        </template>
        <template v-else-if="currentType !== null">
          <h1 class="category_title">{{currentType.name}}</h1>
        </template>
      </div>
      <div class="col-lg-4 col-md-5 col-12 sort my-auto" style="padding: 5px">
        <p>Сортировка:</p>
        <multiselect :value="getSelectSort(sort)"
                     :options="options"
                     @input="onChangeSort"
                     label="name"
                     :searchable="false"
                     selectLabel=""
                     placeholder=""
                     deselectLabel=""
                     selectedLabel=""
                     track-by="value">
        </multiselect>
      </div>

      <div class="col-lg-3 col-md-4 col-12 show_on_page" style="padding: 5px">
        <p>Показать:</p>
        <multiselect :value="getSelectPerPage(perPage)"
                     :options="perPageOptions"
                     @input="onChangePerPage"
                     label="name"
                     :searchable="false"
                     selectLabel=""
                     placeholder=""
                     deselectLabel=""
                     selectedLabel=""
                     track-by="value">
        </multiselect>
      </div>
    </div>
  </div>
</template>

<script>
  import { isMobileOnly } from 'mobile-device-detect';

  export default {
    name: 'Sort',
    props: ['currentCategory', 'currentType', 'selectFilters'],
    created: function () {
      this.options = this.getOptions;
    },
    mounted: function () {
      this.perPage = (this.$route.query.per_page !== null && this.$route.query.per_page !== undefined)
        ? this.$route.query.per_page : this.perPageStore;
      if (this.perPage !== this.perPageStore) {
        this.$store.commit('updatePerPage', this.perPage);
      }
    },
    computed: {
      getIsMobile: function () {
        return isMobileOnly;
      },
      perPageStore: function () {
        return this.$store.getters.perPage;
      },
      getOptions: function () {
        return [
          {value: 'all', name: 'Новинки'},
          {value: 'from_cheap_to_expensive', name: 'от дешевых к дорогим'},
          {value: 'from_expensive_to_cheap', name: 'от дорогих к дешевым'},
          {value: 'popular', name: 'популярные'},
          {value: 'promotional', name: 'акционные'},
        ];
      }
    },
    data() {
      return {
        sort: this.$route.query.sort,
        options: [],
        perPage: null,
        perPageOptions: [
          {value: 16, name: '16 шт.'},
          {value: 32, name: '32 шт.'},
          {value: 50, name: '50 шт.'},
          {value: 100, name: '100 шт.'},
        ]
      }
    },
    methods: {
      getSelectSort: function (value) {
        return this.options.find(item => item.value === value) || {
          name: '',
          value: ''
        }
      },
      getSelectPerPage: function (value) {
        if (value === null) {
          value = (this.$route.query.per_page !== null && this.$route.query.per_page !== undefined)
            ? this.$route.query.per_page : this.perPageStore;
        }

        return this.perPageOptions.find(item => item.value == value) || {
          name: '',
          value: ''
        }
      },
      onChangeSort: function (sort) {
        this.sort = sort.value;
      },
      onChangePerPage: function (perPage) {
        this.perPage = perPage.value;
      }
    },
    watch: {
      '$route.query.sort': function (value) {
        this.sort = value;
      },
      '$route.query.per_page': function (value) {
        if (value !== undefined) {
          this.perPage = value;
        }
      },
      '$route': function (to, from) {
        this.sort = this.$route.query.sort;
      },
      'sort': function (value) {
        this.$store.commit('updateEventApp', true);
        this.$router.push({ query: Object.assign({}, this.$route.query, { sort: value }) });

        this.$emit('updateSort', value);
      },
      'perPage': function (value) {
        this.$router.push({ query: Object.assign({}, this.$route.query, { per_page: value }) });

        this.$store.commit('updatePerPage', value);
        this.$emit('updatePerPage', value);
      }
    }
  }
</script>