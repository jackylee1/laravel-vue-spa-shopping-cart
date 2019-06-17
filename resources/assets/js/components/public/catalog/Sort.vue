<template>
  <div>
    <div class="row titles_top">
      <div class="col-md-6 col-12 my-auto">
        <template v-if="currentCategory !== null">
          <h1 class="category_title">{{currentCategory.name}}</h1>
        </template>
        <template v-else-if="currentType !== null">
          <h1 class="category_title">{{currentType.name}}</h1>
        </template>

        <template v-if="searchByText !== null && searchByText !== undefined && searchByText.length">
          <h4>
            Результат поиска: {{searchByText}}
            <button @click="clearSearchByText"
                    class="btn"
                    style="padding: 5px;max-height: 25px;">
              <i style="margin: 0px" class="fa fa-times"></i>
            </button>
          </h4>
        </template>
      </div>
      <div :class="(getIsMobile) ? 'col-12 sort text-center my-auto' : 'col-md-3 col-6 sort righted my-auto'">
        <p>Сортировка:</p>
      </div>
      <div :style="(getIsMobile) ? 'padding-bottom: 25px;' : ''"
           :class="(getIsMobile) ? 'col-12 my-auto' : 'col-md-3 col-6 my-auto'">
        <multiselect :value="getSelectSort(sort)"
                     :options="options"
                     @input="onChangeSort"
                     label="name"
                     :searchable="false"
                     selectLabel=""
                     placeholder=""
                     deselectLabel=""
                     selectedLabel="Выбрано"
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
      this.searchByText = this.searchByTextStore;
    },
    computed: {
      searchByTextStore: function () {
        return this.$store.getters.searchByText;
      },
      getIsMobile: function () {
        return isMobileOnly;
      },
      getOptions: function () {
        return [
          {value: 'all', name: 'все товары'},
          {value: 'from_cheap_to_expensive', name: 'от дешевых к дорогим'},
          {value: 'from_expensive_to_cheap', name: 'от дорогих к дешевым'},
          {value: 'popular', name: 'популярные'},
          {value: 'new', name: 'новинки'},
          {value: 'promotional', name: 'акционные'},
        ];
      }
    },
    data() {
      return {
        sort: this.$route.query.sort,
        options: [],
        searchByText: null
      }
    },
    methods: {
      clearSearchByText: function () {
        this.$store.commit('updateSearchByText', null);
        this.$router.push({ query: Object.assign(
          {}, this.$route.query, {
            text: null
          }
        )});
        this.$emit('getProducts');
      },
      getSelectSort: function (value) {
        return this.options.find(item => item.value === value) || {
          name: '',
          value: ''
        }
      },
      onChangeSort: function (sort) {
        this.sort = sort.value;
      }
    },
    watch: {
      'searchByTextStore': function (value) {
        this.searchByText = value;
      },
      '$route.query.sort': function (value) {
        this.sort = value;
      },
      '$route': function (to, from) {
        this.sort = this.$route.query.sort;
      },
      'sort': function (value) {
        this.$router.push({ query: Object.assign({}, this.$route.query, { sort: value }) });

        this.$emit('updateSort', value);
      }
    }
  }
</script>