<template>
  <div class="components-container">
    <pan-thumb :image="image" />
    <el-button type="primary" icon="el-icon-upload" :class="{'avatar-desktop': desktop}" @click="imagecropperShow=true">
      Сменить Аватар
    </el-button>
    <image-cropper
      v-show="imagecropperShow"
      :key="imagecropperKey"
      field="file"
      :width="300"
      :height="300"
      :url="url"
      :params="{uid: id, model:'avatar'}"
      lang-type="ru"
      @close="close"
      @crop-upload-success="cropSuccess"

    />
  </div>
</template>

<script>
import ImageCropper from '@/components/ImageCropper'
import PanThumb from '@/components/PanThumb'
import { mapState } from 'vuex'

export default {
  name: 'AvatarUpload',
  components: { ImageCropper, PanThumb },
  props: {
    user_id: {
      type: Number,
      default: 0
    },
    value: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      imagecropperShow: false,
      imagecropperKey: 0,
      image: ''
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    }),
    url() {
      return process.env.VUE_APP_BASE_API + '/user/storage/file'
    },
    desktop() {
      if (this.device === 'desktop') {
        return true
      }
      return false
    },
    user() {
      return this.$store.getters.user
    },
    id() {
      if (this.user_id > 0) {
        return this.user_id
      }
      return this.user.id
    }
  },
  mounted() {
    if (this.id > 0) {
      if (this.value) {
        if (this.value[0] === '/') {
          this.image = process.env.VUE_APP_BASE_API + this.value
        } else {
          this.image = this.value
        }
      } else {
        this.image = '/'
      }
    } else {
      if (this.$store.getters.user.avatar[0] === '/') {
        this.image = process.env.VUE_APP_BASE_API + this.$store.getters.user.avatar
      } else {
        this.image = this.$store.getters.user.avatar
      }
    }
  },
  methods: {
    cropSuccess(resData) {
      console.log('cropSuccess(resData)')
      console.log(resData)
      this.imagecropperShow = false
      this.imagecropperKey = this.imagecropperKey + 1
      this.$emit('input', resData.files.file)
      if (resData.files.file[0] === '/') {
        this.image = process.env.VUE_APP_BASE_API + resData.files.file
      } else {
        this.image = resData.files.file
      }
    },
    close() {
      this.imagecropperShow = false
    }
  }
}
</script>

<style scoped>
  .avatar-desktop{
    position: absolute;
    bottom: 15px;
    margin-left: 40px;
  }
</style>

