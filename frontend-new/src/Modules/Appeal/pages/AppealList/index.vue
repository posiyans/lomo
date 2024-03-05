<template>
  <div class="q-pa-md">
    <AppealList :user-uid="userUid">
      <AppealSettings v-if="showSetting" />
    </AppealList>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import AppealList from 'src/Modules/Appeal/components/AppealList/index.vue'
import AppealSettings from 'src/Modules/Appeal/components/AppealSettings/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

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
    const authStore = useAuthStore()
    const showSetting = computed(() => {
      return authStore.checkPermission(['appeal-setting'])
    })
    return {
      showSetting
    }
  }
})
</script>

<style scoped>

</style>
