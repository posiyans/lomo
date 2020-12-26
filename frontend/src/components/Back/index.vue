<template>
  <span style="padding-left: 20px;">
    <el-button type="primary" size="mini" @click="back">Назад</el-button>
  </span>
</template>

<script>
export default {
  name: 'Back',
  methods: {
    back() {
      this.$router.back()
    }
  }
}
</script>

<style scoped>

</style>
