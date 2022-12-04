<template>
  <div @click="loginVK">
    <img src="/images/vk-banner.png" width="100px">
  </div>
</template>

<script>
export default {
  methods: {
    async loginVK() {
      const $url = await this.$store.dispatch('user/loginVK')
      window.location = $url
    }
  }
}
</script>

<style scoped>

</style>
