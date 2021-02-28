<template>
  <div>
    <el-card>
      <div slot="header" class="clearfix">
        <h2>{{ article.title }}</h2>xcvxcvxcvxcvxcv
      </div>
      <div>
        <span v-html="article.text" />
      </div>
    </el-card>
    article view
  </div>
</template>

<script>
import { fetchUserArticle } from '@/api/article'
export default {
  props: {
    id: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      article: {}
    }
  },
  mounted() {
    this.fetchArticle()
  },
  methods: {
    fetchArticle() {
      fetchUserArticle(this.id)
        .then(response => {
          this.article = response.data
        })
    }
  }
}
</script>

<style scoped>

</style>
