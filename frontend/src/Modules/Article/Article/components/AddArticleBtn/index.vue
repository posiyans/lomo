<template>
  <div>
    <el-button type="primary" @click="showDialog">Сохранить</el-button>
    <el-dialog
      title="Внимание"
      :visible.sync="dialogVisible"
      width="30%"
    >
      <div>Сохранить новость и отправить ее на модерацию?</div>
      <div>После успешной модерации статья будет размещена на сайте.</div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Отмена</el-button>
        <el-button type="primary" @click="saveArticle">Сохранить</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { userUploadFile } from '@/Modules/Files/api/file'
import { userAddArticle } from '@/Modules/Article/Article/api/article'

export default {
  props: {
    article: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      dialogVisible: false,
      chunkSize: 1 * 1024 * 1024,
      current_file: {
        file: {},
        chunk: 1
      }
    }
  },
  computed: {
    maxChunk() {
      return Math.round(this.current_file / this.chunkSize) + 1
    }
  },
  methods: {
    showDialog() {
      this.dialogVisible = true
    },
    saveArticle() {
      const data = {
        'title': this.article.title,
        'text': this.article.text,
        'category_id': this.article.category_id,
        'uid': this.article.uid
      }
      userAddArticle(data)
        .then(res => {
          this.saveFiles()
        })
      this.dialogVisible = false
    },
    saveFiles() {
      this.article.files.forEach(file => {
        this.sendFile(file)
      })
    },
    sendFile(file) {
      const data = {
        file: file,
        chunk: 0,
        maxChunk: Math.round(file.size / this.chunkSize)
      }
      this.sendData(data)
    },
    sendData(data) {
      if (data.chunk <= data.maxChunk) {
        // console.log(this.file.slice(this.start, this.end))
        const start = data.chunk * this.chunkSize
        const end = start + this.chunkSize
        const chunk = data.file.file.slice(start, end)
        const formData = new FormData()
        if (data.chunk === data.maxChunk) {
          formData.append('action', 'done')
          formData.append('name', data.file.name)
          formData.append('size', data.file.size)
          formData.append('type', data.file.type)
          formData.append('model', 'article')
          formData.append('model_uid', this.article.uid)
          data.file.upload = true
        } else {
          formData.append('action', 'chunk')
        }
        formData.append('chunk', data.chunk)
        formData.append('uid', data.file.uid)
        formData.append('file', chunk)
        data.chunk++
        userUploadFile(formData)
          .then(response => {
            this.sendData(data)
          })
      }
    }

  }
}
</script>

<style scoped>

</style>
