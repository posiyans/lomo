<template>
  <div>
    <div v-if="errors" class="page-title text-center q-pa-lg">
      Данная страница не найдена
    </div>
    <ArticleShow v-if="!errors && article" :article="article" />
  </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import ArticleShow from 'src/Modules/Article/Article/components/ArticleShow/index.vue'
import { fetchUserArticle } from 'src/Modules/Article/Article/api/article'
import { useFile } from 'src/Modules/Files/hooks/useFile'

export default defineComponent({
  components: {
    ArticleShow
  },
  setup() {
    const route = useRoute()
    const errors = ref(null)
    const uid = ref(null)
    const article = ref(null)
    const fetchArticle = () => {
      fetchUserArticle(route.params.uid)
        .then(response => {
          errors.value = null
          article.value = response.data.data
          const tmp = []
          if (response.data.data.files) {
            response.data.data.files.forEach(item => {
              const file = useFile()
              file.init(item)
              tmp.push(file)
            })
          }
          article.value.files = tmp
          errors.value = false
        })
        .catch(er => {
          errors.value = true
        })
    }
    onMounted(() => {
      uid.value = route.params.uid
      fetchArticle()
    })
    return {
      uid,
      article,
      errors
    }
  }
})
</script>

<style scoped>

</style>
