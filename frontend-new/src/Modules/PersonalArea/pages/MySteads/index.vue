<template>
  <div>
    <div class="page-title">Мой участок</div>
    <ListOfOwnerStead :owner-uid="authStore.user.owner_uid" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ListOfOwnerStead from 'src/Modules/Stead/components/ListOfOwnerStead/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ListOfOwnerStead
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const authStore = useAuthStore()
    onMounted(() => {

    })
    return {
      data,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
