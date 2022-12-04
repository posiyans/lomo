<template>
  <div @click="loginVK" class="cursor-pointer">
    <img src="~assets/vk-banner.png" width="100">
  </div>
</template>

<script>
import { useUserStore } from 'src/Modules/User/store/user-store'
import { defineComponent } from 'vue'
export default defineComponent({
  setup () {
    const userStore = useUserStore()
    const loginVK = () => {
      userStore.getAuthVkPath()
        .then(url => {
          window.location = url
        })
    }
    return {
      loginVK
    }
  }
})
</script>

<style scoped>

</style>
