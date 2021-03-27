<template>
  <div class="app-container">
    <div v-for="item in list" :key="item.id" class="flex mt2 relative">
      <div class="primary-block__setting" @click="editItem(item)">
        <i class="el-icon-s-tools" />
      </div>
      <div class="pa2" style="max-width: 50%">
        <div class="mb2">
          <strong>Название:</strong>
          {{ item.name }}
        </div>
        <div class="mb2">
          <strong>RTSP:</strong>
          <em>
            {{ item.url }}
          </em>
        </div>
        <div class="mb2">
          <strong>ttl:</strong>
          {{ item.ttl }}
        </div>
      </div>
      <div class="pa2">
        <el-image
          :src="item.id | cameraUrlFilter"
          :preview-src-list="[cameraUrlFilter(item.id)]"
          style="width: 150px"
        >
          <div slot="placeholder" class="image-slot">
            Loading<span class="dot">...</span>
          </div>
        </el-image>
      </div>
    </div>
    <el-button type="primary" @click="addCamera">Добавить</el-button>
    <el-dialog
      title="Камеры"
      :visible.sync="showAddForm"
      :destroy-on-close="false"
      width="600px"
    >
      <AddCamera :item="activeItem" :edit="edit" @reload="close" />
    </el-dialog>
  </div>
</template>

<script>
import { fetchCameraList } from '@/api/admin/setting/camera'
import AddCamera from './components/AddCamera'
export default {
  components: { AddCamera },
  filters: {
    cameraUrlFilter(id) {
      const time = new Date().getTime()
      return process.env.VUE_APP_BASE_API + `/api/v1/all/camera/get-image/${id}?s=${time}.jpg`
    }
  },
  data() {
    return {
      showAddForm: false,
      list: [],
      edit: false,
      activeItem: {}
    }
  },
  created() {
    this.getList()
  },
  methods: {
    editItem(item) {
      this.edit = true
      this.activeItem = item
      this.showAddForm = true
    },
    cameraUrlFilter(id) {
      const time = new Date().getTime()
      return process.env.VUE_APP_BASE_API + `/api/v1/all/camera/get-image/${id}?s=${time}.jpg`
    },
    close() {
      this.showAddForm = false
      this.getList()
    },
    addCamera() {
      this.edit = false
      this.showAddForm = true
    },
    getList() {
      fetchCameraList()
        .then(response => {
          if (response.data.status) {
            this.list = response.data.data
          } else if (response.data.error) {
            this.$message.error(response.data.error)
          }
        })
    }
  }
}
</script>

<style scoped>

</style>
