<template>
  <div>
    <div v-if="errors" class="page-title text-center q-pa-lg">
      Данная страница не найдена
    </div>
    <ArticleShow v-if="!errors && article?.title" :article="article" />
  </div>
</template>

<script>
import { defineComponent, onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import ArticleShow from 'src/Modules/Article/Article/components/ArticleShow/index.vue'
import { fetchUserArticle } from 'src/Modules/Article/Article/api/article'
import { useFile } from 'src/Modules/Files/hooks/useFile'
import { LocalStorage } from 'quasar'

export default defineComponent({
  components: {
    ArticleShow
  },
  setup() {
    const route = useRoute()
    const errors = ref(null)
    const uid = ref(null)
    const article = ref({})
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
    watch(
      () => article.value?.title,
      () => {
        const txt = article.value?.title ? `${article.value.title} - ` : ''
        document.title = txt + LocalStorage.getItem('SiteName') || 'СНТ'
      }
    )
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
