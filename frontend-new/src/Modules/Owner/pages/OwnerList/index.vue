<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-sm q-pb-sm">
      <FilterBlock v-model="listQuery" />
      <div>
        <q-btn color="primary" :loading="fileDownload" label="Скачать" @click="download" />
      </div>
      <q-space />
      <div>
        <q-btn v-if="editable" color="primary" icon="add" flat to="/admin/owner/add" />
      </div>
    </div>
    <ShowTable :list="list" :listQuery="listQuery" />
    <LoadMore v-model:listQuery="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { exportFile } from 'quasar'
import { fetchOwnerListInXlsx, fetchOwnerUserList } from 'src/Modules/Owner/api/ownerUserApi.js'
import ShowTable from './components/ShowTable'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {
    LoadMore,
    FilterBlock,
    ShowTable
  },
  props: {},
  setup(props, { emit }) {
    const list = ref([])
    const fileDownload = ref(false)
    const func = fetchOwnerUserList
    const listQuery = ref(
      {
        page: 1,
        limit: 20,
        find: ''
      }
    )
    const authStore = useAuthStore()
    const editable = computed(() => {
      return authStore.permissions.includes('owner-edit')
    })
    const setList = (val) => {
      list.value = val
    }
    const download = () => {
      fileDownload.value = true
      fetchOwnerListInXlsx(listQuery.value)
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
          fileDownload.value = false
        })
    }
    return {
      list,
      func,
      download,
      fileDownload,
      listQuery,
      setList,
      editable
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
