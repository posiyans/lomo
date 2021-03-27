<template>
  <div class="app-container">
    <h2>Камеры</h2>
    <el-image
      :src="url1"
      :preview-src-list="urlAll"
    />
    <el-image
      :src="url2"
      :preview-src-list="urlAll"
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      src1: '/api/v1/camera/img/1?s=' + new Date().getTime() + '.jpg',
      src2: '/api/v1/camera/img/2?s=' + new Date().getTime() + '.jpg'
    }
  },
  computed: {
    url1() {
      return process.env.VUE_APP_BASE_API + this.src1
    },
    url2() {
      return process.env.VUE_APP_BASE_API + this.src2
    },
    urlAll() {
      return [process.env.VUE_APP_BASE_API + this.src1, process.env.VUE_APP_BASE_API + this.src2]
    }
  }
}
</script>

<style scoped>

</style>
