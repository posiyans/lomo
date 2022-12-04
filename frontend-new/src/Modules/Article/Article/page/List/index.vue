<template>
  <div>
    <div v-for="item in list" :key="item.id" class="q-mb-sm" >
      <ArticlePreview :article="item" />
    </div>
    <LoadMorePagination :key="key" v-model:list-query="listQuery" :func="fetchListForCategory" @setList="setList" />
  </div>
</template>

<script>
import ArticlePreview from 'src/Modules/Article/Article/components/ArticlePreview/index.vue'
import { fetchListForCategory } from 'src/Modules/Article/Article/api/article.js'
import { defineComponent, ref, watch, onBeforeMount } from 'vue'
import { useRoute } from 'vue-router'
import LoadMorePagination from 'components/LoadMorePagination/index.vue'

export default defineComponent({
  components: {
    ArticlePreview,
    LoadMorePagination
  },
  setup () {
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
      listQuery.value.category_id = route.params.uid
    }
    onBeforeMount(() => {
      listQuery.value.category_id = route.params.uid
      // key.value++
    })

    watch(
      () => route.params.uid,
      () => {
        listQuery.value.category_id = route.params.uid
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
