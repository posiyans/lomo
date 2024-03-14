<template>
  <div>
    <div v-if="files.length > 0" class="text-weight-bold">Файлы:</div>
    <FilesListShow :modelValue="files" />
  </div>
</template>

<script>
import { getArticleFiles } from 'src/Modules/Article/Article/api/article'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'

export default {
  components: {
    FilesListShow
  },
  props: {
    articleUid: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      files: []
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    getData() {
      const data = {
        uid: this.articleUid
      }
      getArticleFiles(data)
        .then(res => {
          this.files = res.data
          this.$emit('set-count', this.files.length)
        })
    }
  }
}
</script>

<style scoped>

</style>
