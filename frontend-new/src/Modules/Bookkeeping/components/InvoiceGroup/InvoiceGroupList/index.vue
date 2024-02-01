<template>
  <div>
    <div class="row items-center q-col-gutter-sm">
      <FilterBlock v-model="listQuery" />
      <AddInvoiceGroup @success="reload" />
    </div>
    <ShowTable :list="list" @reload="reload" class="q-pt-sm" />
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import ShowTable from './components/ShowTable/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'
import DownloadXlsxBtn from 'src/Modules/Files/components/DownloadXlsxFileBtn/index.vue'
import { getInvoiceGroupList } from 'src/Modules/Bookkeeping/api/invoiceGroupApi'
import AddInvoiceGroup from 'src/Modules/Bookkeeping/components/InvoiceGroup/AddInvoiceGroup/index.vue'

export default defineComponent({
  components: {
    AddInvoiceGroup,
    ShowTable,
    FilterBlock,
    DownloadXlsxBtn,
    LoadMore
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const func = getInvoiceGroupList
    const listQuery = ref({
      rate_group_id: '',
      page: 1,
      limit: 20
    })
    const reload = () => {
      key.value++
    }
    const setList = (val) => {
      list.value = val
    }
    return {
      listQuery,
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
