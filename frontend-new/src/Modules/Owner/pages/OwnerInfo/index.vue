<template>
  <div class="q-pa-md">
    <ShowOwnerInfo :owner-uid="ownerUid" :edit="editable" admin />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import ShowOwnerInfo from 'src/Modules/Owner/components/ShowOwnerInfo/index.vue'

export default defineComponent({
  components: {
    ShowOwnerInfo
  },
  props: {},
  setup(props, { emit }) {
    const route = useRoute()
    const ownerUid = computed(() => {
      return route.params.uid
    })
    const authStore = useAuthStore()
    const editable = computed(() => {
      return authStore.checkPermission('owner-edit')
    })

    return {
      ownerUid,
      editable
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

