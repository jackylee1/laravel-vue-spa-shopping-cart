<template>
  <div>
    <Breadcrumbs :items="breadcrumbs"/>

    <section class="item_card">
      <div class="container">
        <Errors :type="typeAlerts"
                :alerts="alerts"/>
      </div>
    </section>
  </div>
</template>

<script>
  import Breadcrumbs from "../Breadcrumbs";
  import mixinAlerts from '../../../app/public/mixins/Alerts';
  import Errors from "../Errors";

  export default {
    name: 'NotFound',
    mixins: [mixinAlerts],
    mounted() {
      this.alerts = 'Страница не существует';
      this.$notify({
        type: 'error',
        title: 'Ошибка',
        text: 'при получении текстовой страницы'
      });
    },
    data() {
      return {
        breadcrumbs: [],
      }
    },
    components: {
      Errors,
      Breadcrumbs
    }
  }
</script>