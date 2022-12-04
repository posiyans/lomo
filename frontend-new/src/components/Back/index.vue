<template>
  <q-btn color="primary" no-wrap no-caps label="Назад" @click="back"/>
</template>

<script>
export default {
  name: 'Back',
  methods: {
    back () {
      this.$router.back()
    }
  }
}
</script>

<style scoped>

</style>
