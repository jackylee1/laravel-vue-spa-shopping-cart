<template>
  <div>
    <template v-if="alerts instanceof Object">
      <template v-for="errors in alerts">
        <template v-for="message in errors">
          <el-alert
              :closable="false"
              style="padding: 10px;margin-bottom: 10px;margin-top: 15px;"
              :title="message"
              :type="type">
          </el-alert>
        </template>
      </template>
    </template>
    <template v-else="alerts instanceof String">
      <el-alert
          style="padding: 10px;margin-bottom: 10px;"
          :title="alerts"
          :type="type">
      </el-alert>
    </template>
  </div>
</template>

<script>
  export default {
    name: 'page-elements-alerts',
    props: ['alerts', 'type']
  }
</script>