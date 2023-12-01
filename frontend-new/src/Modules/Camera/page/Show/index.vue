<template>
  <div class="q-pa-sm">
    <div class="page-title">
      Камеры
    </div>
    <q-card>
      <q-card-section>
        <div v-for="item in list" :key="item.id" class="q-my-auto">
          <ShowCamera :item="item" show-name />
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { getCameraList } from 'src/Modules/Camera/api/camera.js'
import ShowCamera from 'src/Modules/Camera/components/ShowCamera/index.vue'

export default {
  components: {
    ShowCamera
  },
  data() {
    return {
      list: []
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      getCameraList()
        .then(response => {
          this.list = response.data.data
        })
    }

  }
}
</script>

<style scoped>
</style>
