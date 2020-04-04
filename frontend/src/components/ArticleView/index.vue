<template>
  <div>
    <el-card>
      <div slot="header" class="clearfix">
        <h2>{{ article.title }}</h2>
      </div>
      <div>
        <span v-html="article.text">

        </span>
      </div>
    </el-card>
    article view
  </div>
</template>

<script>
  import { fetchUserArticle } from "@/api/article"
  export default {
    props: {
      id: {
        type: Number,
        default: 0
      },
    },
    mounted() {
      this.fetchArticle()
    },
    data() {
      return {
        article: {}
      }
    },
    methods: {
      fetchArticle(){
        console.log('fetch article')
        console.log(this.id)
        fetchUserArticle(this.id)
          .then(response => {
            console.log(response)
            this.article = response.data
          })
      }
    }
  }
</script>

<style scoped>

</style>
