<template>
  <ul v-if="resultProductsAutoComplete.length"
      class="typeahead dropdown-menu"
      role="listbox" style="top: 60px; margin-left: 10px; display: block;">
    <template v-for="product in resultProductsAutoComplete">
      <li class="" @click="selectResultAutoComplete(product.name)">
        <a class="dropdown-item" href="#" role="option">{{product.name}}</a>
      </li>
    </template>
  </ul>
</template>

<script>
  export default {
    name: 'ListProductsAutoComplete',
    props: ['resultProductsAutoComplete'],
    methods: {
      selectResultAutoComplete: function (value) {
        this.$emit('selectResultAutoComplete', value);
      }
    }
  }
</script>