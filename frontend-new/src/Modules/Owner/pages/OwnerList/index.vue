<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-sm q-pb-sm">
      <FilterBlock v-model="listQuery" />
      <div>
        <q-btn color="primary" label="XLSX" @click="download" />
      </div>
      <q-space />
      <div>
        <q-btn v-if="editable" color="primary" icon="add" flat to="/admin/owner/add" />
      </div>
    </div>
    <ShowTable :list="list" />
    <LoadMore v-model:listQuery="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { exportFile, Notify } from 'quasar'
import { fetchOwnerListInXlsx, fetchOwnerUserList } from 'src/Modules/Owner/api/ownerUserApi.js'
import ShowTable from './components/ShowTable'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'

export default defineComponent({
  components: {
    LoadMore,
    FilterBlock,
    ShowTable
  },
  props: {},
  setup(props, { emit }) {
    const list = ref([])
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
      Notify.success('Файл успешно скачан.')
      fetchOwnerListInXlsx(this.listQuery).then(response => {
        const blob = new Blob([response.data])
        exportFile('Список.xlsx', blob)
        this.$message('Файл успешно скачан.')
      })
    }
    return {
      list,
      func,
      download,
      listQuery,
      setList,
      editable
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
