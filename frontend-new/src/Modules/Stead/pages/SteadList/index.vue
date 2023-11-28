<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-md q-pb-sm">
      <FilterBlock v-model="listQuery" />
      <div>
        <q-btn :loading="downloadLoading" color="primary" label="XLSX" @click="handleDownload" />
      </div>
      <div>
        <AddStead @success="handleFilter" />
      </div>
    </div>
    <TableBlock :list="list" />
    <LoadMore :key="key" v-model:listQuery="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { fetchSteadListInXlsx } from 'src/Modules/Stead/api/steadAdminApi.js'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'
import { getSteadsList } from 'src/Modules/Stead/api/stead'
import AddStead from 'src/Modules/Stead/components/AddStead/index.vue'
import TableBlock from './components/TableBlock/index.vue'
import { exportFile, Notify } from 'quasar'

export default defineComponent({
  components: {
    LoadMore,
    FilterBlock,
    TableBlock,
    AddStead
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(false)
    const downloadLoading = ref(false)
    const list = ref([])
    const func = getSteadsList
    const setList = (val) => {
      list.value = val
    }
    const handleFilter = (val) => {
      key.value++
    }
    const handleDownload = () => {
      Notify.success('Упс.')
      fetchSteadListInXlsx(this.listQuery).then(response => {
        exportFile('Список.xlsx', new Blob([response.data]))
        Notify.success('Файл успешно скачан.')
      })
    }
    const listQuery = ref({
      page: 1,
      limit: 20,
      find: ''
    })
    return {
      list,
      setList,
      handleFilter,
      downloadLoading,
      handleDownload,
      key,
      func,
      listQuery
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
