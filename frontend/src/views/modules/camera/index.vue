<template>
  <div class="app-container">
    <h2>Камеры</h2>
    <img class="camera_img" src="/camera/img/1"/>
    <img class="camera_img" src="/camera/img/2"/>
  </div>
</template>

<script>
export default {
  mounted() {
    // fetch('/camera')
  }
}
</script>

<style scoped>
  .camera_img{
    width: 100%;
  }
</style>
