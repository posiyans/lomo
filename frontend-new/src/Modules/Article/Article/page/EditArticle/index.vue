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
import { uid } from 'quasar'

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
          status: 0,
          title: '',
          text: '',
          resume: '',
          category_id: '',
          allow_comments: 0,
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
      key
    }
  }
})
</script>

<style scoped>

</style>
