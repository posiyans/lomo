<template>
  <div class="app-container">
    <div class="text-h6">Камеры</div>
    <q-separator />

    <div v-for="item in list" :key="item.id" class="row justify-center">
      <ShowCamera :item="item" />
    </div>
  </div>
</template>

<script>
import { getCameraList } from 'src/Modules/Camera/api/camera.js'
import ShowCamera from 'src/Modules/Camera/components/ShowCamera/index.vue'

export default {
  components: {
    ShowCamera
  },
  data () {
    return {
      list: []
    }
  },
  mounted () {
    this.getList()
  },
  methods: {
    getList () {
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
</style>
