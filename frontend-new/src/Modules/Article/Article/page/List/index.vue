<template>
  <div>
    <div class="page-title">
      <CategoryShow v-model="listQuery.category_id" />
    </div>
    <div v-for="item in list" :key="item.id" class="q-mb-sm">
      <ArticlePreview :article="item" />
    </div>
    <LoadMore :key="key" v-model:list-query="listQuery" :func="fetchListForCategory" change-url @setList="setList" />
  </div>
</template>

<script>
import ArticlePreview from 'src/Modules/Article/Article/components/ArticlePreview/index.vue'
import { fetchListForCategory } from 'src/Modules/Article/Article/api/article.js'
import { defineComponent, onBeforeMount, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import LoadMore from 'components/LoadMore/index.vue'
import CategoryShow from 'src/Modules/Article/Category/components/CategoryShow/index.vue'

export default defineComponent({
  components: {
    CategoryShow,
    ArticlePreview,
    LoadMore
  },
  setup() {
    const route = useRoute()
    const list = ref([])
    const key = ref(1)
    const listQuery = ref({
      category_id: null,
      page: 1,
      limit: 20,
      sort: '-time'
    })
    const setList = (val) => {
      list.value = val
    }
    const getData = () => {
      list.value = []
      listQuery.value.category_id = route.params.id
    }
    onBeforeMount(() => {
      listQuery.value.category_id = route.params.id
      // key.value++
    })

    watch(
      () => route.params.id,
      () => {
        listQuery.value.category_id = route.params.id
        listQuery.value.page = 1
        listQuery.value.limit = 20
        listQuery.value.sort = '-time'
        key.value++
        getData()
      }
    )

    return {
      list,
      key,
      listQuery,
      setList,
      fetchListForCategory
    }
  }
})
</script>

<style scoped>

</style>
