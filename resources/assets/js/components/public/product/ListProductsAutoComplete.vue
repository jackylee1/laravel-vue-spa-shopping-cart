<template>
  <ul v-if="resultProductsAutoComplete.length"
      class="typeahead dropdown-menu"
      role="listbox" :style="(!getIsMobileOnly) ? 'top: 60px; margin-left: 10px; display: block;'
                                      : 'top: 60px; margin-left: 10px; display: block;width:97%'">
    <template v-for="product in resultProductsAutoComplete">
      <li class="" style="overflow:hidden;" @click="selectResultAutoComplete(product.name)">
        <a class="dropdown-item" href="#" role="option">{{product.name}}</a>
      </li>
    </template>
  </ul>
</template>

<script>
  import { isMobileOnly } from 'mobile-device-detect';

  export default {
    name: 'ListProductsAutoComplete',
    props: ['resultProductsAutoComplete'],
    computed: {
      getIsMobileOnly: function () {
        return isMobileOnly;
      }
    },
    methods: {
      selectResultAutoComplete: function (value) {
        this.$emit('selectResultAutoComplete', value);
      }
    }
  }
</script>