<template>
  <div>
    <ActiveUserCart />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useActiveUserStore } from 'src/Modules/Users/stores/useActiveUserStore'
import ActiveUserCart from 'src/Modules/Users/components/ActiveUserCart/index.vue'

export default defineComponent({
  components: {
    ActiveUserCart
  },
  props: {},
  setup() {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const dialogVisible = ref(false)
    const activeUserStore = useActiveUserStore()

    const showDialog = () => {
      activeUserStore.init(route.params.uid)
      activeUserStore.getUserInfo()
      dialogVisible.value = true
    }
    onMounted(() => {
      showDialog()
    })
    return {
      data
    }
  }
})
</script>

<style scoped>

</style>
