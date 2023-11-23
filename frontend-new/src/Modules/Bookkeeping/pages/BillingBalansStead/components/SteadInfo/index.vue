<template>
  <div>
    <el-tabs type="border-card">
      <el-tab-pane label="Участок">
        <Stead :stead="stead" />
      </el-tab-pane>
      <el-tab-pane label="Владелец">
        <Owner v-if="stead" :stead="stead" />
      </el-tab-pane>
      <el-tab-pane label="Приборы учета">
        <MeteringDevices :stead="stead" />
      </el-tab-pane>
      <!--      <el-tab-pane label="Геометрия">-->
      <!--        <Geometry :stead="stead" />-->
      <!--      </el-tab-pane>-->
    </el-tabs>
  </div>
</template>

<script>
import { fetchSteadInfo } from 'src/Modules/Stead/api/steadAdminApi.js'
import Stead from './components/Stead'
import Owner from './components/Owner'
import MeteringDevices from './components/MeteringDevices'
// import Geometry from './components/Geometry'

export default {
  components: {
    Stead,
    Owner,
    MeteringDevices
    // Geometry
  },
  data() {
    return {
      stead: null,
      id: ''
    }
  },
  mounted() {
    this.id = this.$route.params && this.$route.params.id
    this.getInfo()
  },
  methods: {
    getInfo() {
      fetchSteadInfo(this.id)
        .then(response => {
          if (response.data.status) {
            this.stead = response.data.data
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
