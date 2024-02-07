<template>
  <div class="q-pa-sm">
    <div class="row q-mb-xs">
      <FilterBlock v-model="listQuery" />
      <q-space />
      <AddInstrumentReading :stead-id="listQuery.stead_id" @success="reload" />
      <DownloadXlsxFileBtn :func="xlsxFunc" />
    </div>
    <TableBlock :list="groupList" :edit="edit" @reload="reload" />
    <LoadMore :key="key" v-model:listQuery="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import FilterBlock from './components/FilterBlock/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import { getInstrumentReadingList, getInstrumentReadingListXlsx } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'
import TableBlock from './components/TableBlock/index.vue'
import DownloadXlsxFileBtn from 'src/Modules/Files/components/DownloadXlsxFileBtn/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import AddInstrumentReading from 'src/Modules/MeteringDevice/components/AddInstrumentReading/index.vue'

export default defineComponent({
  components: {
    FilterBlock,
    LoadMore,
    TableBlock,
    DownloadXlsxFileBtn,
    AddInstrumentReading
  },
  props: {},
  setup(props, { emit }) {
    const authStore = useAuthStore()
    const edit = computed(() => {
      return authStore.checkPermission(['reading-edit'])
    })
    const key = ref(1)
    const list = ref([])
    const func = getInstrumentReadingList
    const listQuery = ref({
      stead_id: '',
      rate_group_id: '',
      rate_type_id: '',
      date_start: '',
      date_end: '',
      group_by: '',
      page: 1,
      limit: 20
    })
    const xlsxFunc = computed(() => {
      const data = Object.assign({}, listQuery.value)
      data.xlsx = 1
      return () => {
        return getInstrumentReadingListXlsx(data)
      }
    })
    const reload = () => {
      key.value++
    }
    const groupList = computed(() => {
      if (listQuery.value.group_by === 'date') {
        const rez = []
        let old = null
        list.value.filter(item => {
          delete item.extend
        })
        list.value.filter(item => {
          if (old && old.device.stead_id === item.device.stead_id && old.date === item.date) {
            if (old.extend) {
              old.extend.push(item)
            } else {
              old.extend = [item]
            }
          } else {
            rez.push(item)
          }
          old = item
        })
        return rez
      }
      return list.value
    })
    const setList = (val) => {
      list.value = val
    }
    return {
      key,
      reload,
      list,
      listQuery,
      groupList,
      func,
      edit,
      xlsxFunc,
      setList
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
