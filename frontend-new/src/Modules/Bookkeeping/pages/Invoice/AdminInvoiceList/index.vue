<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-sm">
      <FilterBlock v-model="listQuery" />
      <DownloadXlsxBtn :func="funcXlsx" />
    </div>
    <ShowTable :list="list" @reload="reload" class="q-pt-sm" />
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowTable from './components/ShowTable/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'
import { getInvoiceList, getInvoiceListXlsx } from 'src/Modules/Bookkeeping/api/invoiceAdminApi'
import DownloadXlsxBtn from 'src/Modules/Files/components/DownloadXlsxFileBtn/index.vue'

export default defineComponent({
  components: {
    ShowTable,
    FilterBlock,
    DownloadXlsxBtn,
    LoadMore
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const func = getInvoiceList
    const listQuery = ref({
      stead_id: '',
      is_paid: 0,
      rate_group_id: '',
      page: 1,
      limit: 20
    })
    const funcXlsx = computed(() => {
      const tmp = Object.assign({}, listQuery.value)
      tmp.xlsx = 1
      return () => {
        return getInvoiceListXlsx(tmp)
      }
    })
    const reload = () => {
      key.value++
    }
    const setList = (val) => {
      list.value = val
    }
    return {
      listQuery,
      funcXlsx,
      key,
      reload,
      setList,
      func,
      list
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
