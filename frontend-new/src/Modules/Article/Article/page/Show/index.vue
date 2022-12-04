<template>
  <div>
    <ArticleShow v-if="article" :article="article" />
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import ArticleShow from 'src/Modules/Article/Article/components/ArticleShow/index.vue'
import { fetchUserArticle } from 'src/Modules/Article/Article/api/article'

export default defineComponent({
  components: {
    ArticleShow
  },
  setup () {
    const route = useRoute()
    const uid = ref(null)
    const article = ref(null)
    const fetchArticle = () => {
      fetchUserArticle(route.params.uid)
        .then(response => {
          article.value = response.data
        })
    }
    onMounted(() => {
      uid.value = route.params.uid
      fetchArticle()
    })
    return {
      uid,
      article
    }
  }
})
</script>

<style scoped>

</style>
