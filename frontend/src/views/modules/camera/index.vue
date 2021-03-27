<template>
  <div class="app-container">
    <h2>Камеры</h2>
    <div v-for="item in list" :key="item.id">
      <div class="page-title">{{ item.name }}</div>
      <div>
        <el-image
          :src="item.id | cameraUrlFilter"
          :preview-src-list="urlAll"
        >
          <div slot="placeholder" class="image-slot">
            Loading<span class="dot">...</span>
          </div>
        </el-image>
      </div>
    </div>
  </div>
</template>

<script>
import { getCameraList } from '@/api/all/camera'
export default {
  filters: {
    cameraUrlFilter(id) {
      const time = new Date().getTime()
      return process.env.VUE_APP_BASE_API + `/api/v1/all/camera/get-image/${id}?s=${time}.jpg`
    }
  },
  data() {
    return {
      list: []
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
      const data = []
      const time = new Date().getTime()
      this.list.forEach(item => {
        data.push(process.env.VUE_APP_BASE_API + `/api/v1/all/camera/get-image/${item.id}?s=${time}.jpg`)
      })
      return data
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      getCameraList()
        .then(response => {
          if (response.data.status) {
            this.list = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    }

  }
}
</script>

<style scoped>
 .image-slot {
   min-width: 300px;
   display: block;
   min-height: 200px;
   padding-top: 100px;
   text-align: center;
   border: 1px solid #a4a4a4;
 }
</style>
