<template>
  <div class="q-pa-md">
    <div v-if="loading" class="text-center q-pa-lg">
      <q-spinner
        color="primary"
        size="5em"
        :thickness="2"
      />
    </div>
    <div v-else>
      <div v-if="noFound" class="text-center text-h4 q-pa-lg">
        Статья не найдена
      </div>
      <EditArticleForm v-else />
    </div>
  </div>
</template>

<script>
import EditArticleForm from 'src/Modules/Article/Article/components/EditArticleForm/index.vue'
import { defineComponent, onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useArticleStore } from 'src/Modules/Article/Article/stores/useArticleStore'
import { uid } from 'quasar'

export default defineComponent({
  components: {
    EditArticleForm
  },
  props: {},
  setup() {
    const data = ref(null)
    const noFound = ref(false)
    const loading = ref(true)
    const key = ref(1)
    const route = useRoute()
    const articleStore = useArticleStore()
    watch(route, () => getData())
    const getData = () => {
      loading.value = true
      const id = route.params.id
      if (id) {
        articleStore.init(id)
        articleStore.getData()
          .then(() => {
            noFound.value = false
          })
          .catch(er => {
            if (er.response.status === 404) {
              noFound.value = true
            }
          })
          .finally(() => {
            loading.value = false
          })
      } else {
        articleStore.init(null)
        articleStore.article = {
          status: 1,
          title: '',
          text: '',
          resume: '',
          category_id: '',
          allow_comments: 1,
          uid: uid()
        }
      }
      key.value++
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      noFound,
      loading,
      key
    }
  }
})
</script>

<style scoped>

</style>
