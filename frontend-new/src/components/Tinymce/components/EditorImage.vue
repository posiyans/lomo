<template>
  <div class="upload-container">
    <el-button :style="{background:color,borderColor:color}" icon="el-icon-upload" size="mini" type="primary" @click=" dialogVisible=true">
      Загрузить
    </el-button>
    <el-dialog v-model="dialogVisible">
      <el-upload
        :multiple="true"
        :file-list="fileList"
        :show-file-list="true"
        :on-remove="handleRemove"
        :on-success="handleSuccess"
        :before-upload="beforeUpload"
        class="editor-slide-upload"
        with-credentials
        :headers="token"
        :data="{uid: id, model:'article'}"
        :action="url"
        list-type="picture-card"
      >
        <el-button size="small" type="primary">
          Загрузить
        </el-button>
      </el-upload>
      <el-button @click="dialogVisible = false">
        Отмена
      </el-button>
      <el-button type="primary" @click="handleSubmit">
        Подтвердить
      </el-button>
    </el-dialog>
  </div>
</template>

<script>
import { uploadFile } from '@/api/file'

export default {
  name: 'EditorSlideUpload',
  props: {
    color: {
      type: String,
      default: '#1890ff'
    },
    id: {
      type: String,
      default: 'no_uid'
    }
  },
  data() {
    return {
      dialogVisible: false,
      listObj: {},
      fileList: [],
      token: { 'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN') }
    }
  },
  computed: {
    url() {
      return process.env.VUE_APP_BASE_API + '/api/user/storage/file'
    }
  },
  methods: {
    getCookie(name) {
      const matches = document.cookie.match(new RegExp(
        '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'
      ))
      return matches ? decodeURIComponent(matches[1]) : undefined
    },
    checkAllSuccess() {
      console.log('checkAllSuccess()')
      console.log(this.listObj)
      return Object.keys(this.listObj).every(item => this.listObj[item].hasSuccess)
    },
    handleSubmit() {
      console.log('handleSubmit()')
      const arr = Object.keys(this.listObj).map(v => this.listObj[v])
      if (!this.checkAllSuccess()) {
        this.$message('Please wait for all images to be uploaded successfully. If there is a network problem, please refresh the page and upload again!')
        return
      }
      this.$emit('successCBK', arr)
      this.listObj = {}
      this.fileList = []
      this.dialogVisible = false
    },
    handleSuccess(response, file) {
      console.log('handleSuccess')
      console.log(response)
      console.log(file)
      const uid = file.uid
      const objKeyArr = Object.keys(this.listObj)
      for (let i = 0, len = objKeyArr.length; i < len; i++) {
        if (this.listObj[objKeyArr[i]].uid === uid) {
          this.listObj[objKeyArr[i]].url = response.files.file
          // this.listObj[objKeyArr[i]].url = 'https://sun9-23.userapi.com/c638430/v638430487/359b2/eoc8yTAibjo.jpg?ava=1'
          this.listObj[objKeyArr[i]].hasSuccess = true
          return
        }
      }
    },
    handleRemove(file) {
      console.log('handleRemove(file)')
      console.log(file)
      const uid = file.uid
      const objKeyArr = Object.keys(this.listObj)
      for (let i = 0, len = objKeyArr.length; i < len; i++) {
        if (this.listObj[objKeyArr[i]].uid === uid) {
          delete this.listObj[objKeyArr[i]]
          return
        }
      }
    },
    beforeUpload(file) {
      console.log('beforeUpload(file)')
      console.log(file)
      console.log(document.cookie)
      console.log(this.getCookie('XSRF-TOKEN'))

      const _self = this
      const _URL = window.URL || window.webkitURL
      console.log(_URL)
      const fileName = file.uid
      this.listObj[fileName] = { uid: file.uid }
      return new Promise((resolve, reject) => {
        const img = new Image()
        img.src = _URL.createObjectURL(file)
        console.log(img.src)
        img.onload = function() {
          _self.listObj[fileName] = { hasSuccess: false, uid: file.uid, width: this.width, height: this.height }
        }
        resolve(true)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.editor-slide-upload {
  margin-bottom: 20px;
  >>> .el-upload--picture-card {
    width: 100%;
  }
}
</style>
