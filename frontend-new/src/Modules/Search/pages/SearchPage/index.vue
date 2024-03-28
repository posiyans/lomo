<template>
  <div class="bg-white">
    <div class="page-title">
      Поиск по сайту
    </div>
    <div class="q-px-md row items-center">
      <div class="col-grow">
        <q-input v-model="listQuery.find" label="Найти" dense outlined clearable @keydown.enter="findText" @clear="findText" />
      </div>
      <div>
        <q-btn label="Найти" flat color="primary" @click="findText" />
      </div>
    </div>
    <div class="q-pa-md">
      Результаты поиска:
    </div>
    <div v-for="item in list" :key="item.url">
      <SearchPreview :item="item" />
    </div>
    <div v-if="list.length === 0" class="q-pa-md text-grey-8">
      По вашему запросу ничего не найдено
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { searchBySite } from 'src/Modules/Search/api/search'
import SearchPreview from 'src/Modules/Search/components/SearchPreview/index.vue'

export default defineComponent({
  components: {
    SearchPreview
  },
  props: {},
  setup(props, { emit }) {
    const route = useRoute()
    const router = useRouter()
    const list = ref([])
    const listQuery = ref({
      page: 1,
      limit: 30,
      find: route.query.find || ''
    })
    const getData = () => {
      if (!!listQuery.value.find) {
        searchBySite(listQuery.value)
          .then(res => {
            list.value = res.data.data || []
          })
          .catch(() => {
            list.value = []
          })
      } else {
        list.value = []
      }
    }
    const findText = () => {
      router.replace({ path: route.fullPath, query: { find: listQuery.value.find } })
    }
    const changeRoute = () => {
      listQuery.value.find = route.query.find || ''
      getData()
    }
    onMounted(() => {
      getData()
    })
    watch(
      () => route.query.find,
      () => changeRoute()
    )
    return {
      listQuery,
      list,
      findText,
      getData

    }

  }
})
</script>

<style scoped lang='scss'>

</style>
