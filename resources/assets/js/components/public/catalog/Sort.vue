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
      </div>
      <div class="col-md-3 col-6 sort righted my-auto">
        <p>Сортировка:</p>
      </div>
      <div class="col-md-3 col-6 my-auto">
        <multiselect :value="getSelectSort(sort)"
                     :options="options"
                     @input="onChangeSort"
                     label="name"
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
  export default {
    name: 'Sort',
    props: ['currentCategory', 'currentType', 'selectFilters'],
    created: function () {
      this.options = this.getOptions;
    },
    computed: {
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
        options: []
      }
    },
    methods: {
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