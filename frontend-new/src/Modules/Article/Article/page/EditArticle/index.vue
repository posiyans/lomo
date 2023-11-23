<template>
  <div class="q-pa-md">
    <EditArticleForm />
  </div>
</template>

<script>
import EditArticleForm from 'src/Modules/Article/Article/components/EditArticleForm/index.vue'
import { defineComponent, onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useArticleStore } from 'src/Modules/Article/Article/stores/useArticleStore'
import { LocalStorage, uid } from 'quasar'

export default defineComponent({
  components: {
    EditArticleForm
  },
  props: {},
  setup() {
    const data = ref(null)
    const key = ref(1)
    const route = useRoute()
    const articleStore = useArticleStore()
    watch(route, () => getData())
    const getData = () => {
      const id = route.params.id
      if (id) {
        articleStore.init(id)
        articleStore.getData()
      } else {
        articleStore.init(null)
        articleStore.article = {
          public: 0,
          status: 0,
          title: '',
          text: '',
          resume: '',
          news: 1,
          category_id: 17,
          allow_comments: 0,
          uid: uid()
        }
      }
      key.value++
    }
    const getCookie = function (name) {
      const value = `; ${document.cookie}`
      const parts = value.split(`; ${name}=`)
      if (parts.length === 2) return parts.pop().split(';').shift()
    }
    onMounted(() => {
      document.cookie = 'X-XSRF-TOKEN=' + getCookie('XSRF-TOKEN') + '; path=/; expires=Tue, 19 Jan 2038 03:14:07 GMT; samesite=Lax'
      const token = LocalStorage.getItem('UserToken') || ''
      // if (token) {
      document.cookie = 'Authorization = Bearer ' + token
      getData()
    })
    return {
      data,
      key
    }
  }
})
</script>

<style scoped>

</style>
