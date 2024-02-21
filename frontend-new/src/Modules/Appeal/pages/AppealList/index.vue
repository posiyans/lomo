<template>
  <div class="q-pa-md">
    <AppealList>
      <AppealSettings />
    </AppealList>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import AppealList from 'src/Modules/Appeal/components/AppealList/index.vue'
import AppealSettings from 'src/Modules/Appeal/components/AppealSettings/index.vue'

export default defineComponent({
  components: {
    AppealList,
    AppealSettings
  },
  props: {
    userUid: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    return {}
  }
})
</script>

<style scoped>

</style>
