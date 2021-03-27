<template>
  <div>
    <el-form label-position="top">
      <el-form-item label="Название" :required="true">
        <el-input v-model="camera.name" placeholder="что за камера" />
      </el-form-item>
      <el-form-item label="Путь до rtsp потока" :required="true">
        <el-input v-model="camera.url" placeholder="rtsp://user:password@10.10.10.10:1234/ip01/1" />
      </el-form-item>
      <el-form-item label="TTL" :required="true">
        <el-input v-model="camera.ttl" type="number" min="300" placeholder="Время обновления картинки" />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="close">Отмена</el-button>
      <el-button type="primary" @click="saveData">{{ buttonTitle }}</el-button>
    </span>
  </div>
</template>

<script>
import { addCamera, updateCamera } from '@/api/admin/setting/camera'
export default {
  props: {
    edit: {
      type: Boolean,
      default: false
    },
    item: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      camera: {
        name: '',
        url: '',
        ttl: 3600
      }
    }
  },
  computed: {
    buttonTitle() {
      if (this.edit) {
        return 'Сохранить'
      }
      return 'Добавить'
    }
  },
  mounted() {
    if (this.edit) {
      this.camera = Object.assign({}, this.item)
    }
  },
  methods: {
    close() {
      this.$emit('reload')
    },
    saveData() {
      if (this.edit) {
        updateCamera(this.camera)
          .then(response => {
            if (response.data.status) {
              this.$message('Данные успешно сохранены')
              this.close()
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      } else {
        addCamera(this.camera)
          .then(response => {
            if (response.data.status) {
              this.$message('Данные успешно сохранены')
              this.close()
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      }
    }

  }
}
</script>

<style scoped>

</style>
