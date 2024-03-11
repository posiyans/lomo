<template>
  <div class="">
    <div class="page-title">
      Поиск по сайту
    </div>
    <div class="q-pa-md">
      Результаты поиска по фразе: {{ listQuery.find }}
    </div>
    <div>
      <q-input v-model="listQuery.find" dense outlined @keydown.enter="getData" />
    </div>
    <div v-for="item in list" :key="item.url">
      <SearchPreview :item="item" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { searchBySite } from 'src/Modules/Search/api/search'
import SearchPreview from 'src/Modules/Search/components/SearchPreview/index.vue'

export default defineComponent({
  components: {
    SearchPreview
  },
  props: {},
  setup(props, { emit }) {
    const route = useRoute()
    const list = ref([])
    const listQuery = ref({
      page: 1,
      limit: 20,
      find: route.query.find || ''
    })
    const getData = () => {
      if (listQuery.value.find !== '') {
        searchBySite(listQuery.value)
          .then(res => {
            list.value = res.data.data || []
          })
      } else {
        list.value = []
      }
    }
    const changeRoute = () => {
      listQuery.value.find = route.query.find
      getData()
    }
    onMounted(() => {
      getData()
    })
    watch(
      () => route.query.find,
      () => getData()
    )
    return {
      listQuery,
      list,
      getData

    }

  }
})
</script>

<style scoped lang='scss'>

</style>
