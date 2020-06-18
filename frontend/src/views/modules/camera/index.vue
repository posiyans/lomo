<template>
  <div class="app-container">
    <h2>Камеры</h2>
    <img class="camera_img" :src="src1" @click="limgClick(1)" />
    <img class="camera_img" :src="src2" @click="limgClick(2)" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      src1: '/api/v1/camera/img/1',
      src2: '/api/v1/camera/img/2'
    }
  },
  mounted() {
    // fetch('/camera')
  },
  methods: {
    limgClick(val) {
      // if (val === 1) {
      //   if (this.src1 === '/camera/img/1') {
      //     this.src1 = '/camera/img/gif1'
      //   } else {
      //     this.src1 = '/camera/img/1'
      //   }
      // }
      // if (val === 2) {
      //   if (this.src2 === '/camera/img/2') {
      //     this.src2 = '/camera/img/gif2'
      //   } else {
      //     this.src2 = '/camera/img/2'
      //   }
      // }
    }
  }
}
</script>

<style scoped>
  .camera_img{
    width: 100%;
  }
</style>
