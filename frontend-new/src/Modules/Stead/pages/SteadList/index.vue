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
    <LoadMore :key="key" v-model:listQuery="listQuery" change-url :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'
import { fetchSteadListInXlsx, getSteadsList } from 'src/Modules/Stead/api/stead'
import AddStead from 'src/Modules/Stead/components/AddStead/index.vue'
import TableBlock from './components/TableBlock/index.vue'
import { exportFile } from 'quasar'
import { errorMessage } from 'src/utils/message'

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
      downloadLoading.value = true
      fetchSteadListInXlsx(listQuery.value)
        .then(response => {
          let fileName = response.headers['content-disposition'].split('filename=')[1].split(';')[0]
          try {
            fileName = decodeURIComponent(response.headers['content-disposition'].split("filename*=utf-8''")[1].split(';')[0])
          } catch (e) {
          }
          const status = exportFile(fileName, response.data)
          if (status !== true) {
            errorMessage('Ошибка получения файла')
          }
        })
        .catch(() => {
          errorMessage('Файл не найден')
        })
        .finally(() => {
          downloadLoading.value = false
        })
    }
    const listQuery = ref({
      page: 1,
      limit: 20,
      admin: 1,
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
