<template>
  <div>
    <FilesListShow :value="files" />
  </div>
</template>

<script>
import { getArticleFiles } from '@/Modules/Article/Article/api/article'
import FilesListShow from '@/Modules/Files/components/FilesListShow'

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
