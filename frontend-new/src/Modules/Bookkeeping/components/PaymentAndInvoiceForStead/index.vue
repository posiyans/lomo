<template>
  <div>
    <BalanceForStead :stead-id="steadId" class="q-pa-sm" />
    <div class="row items-center q-col-gutter-sm">
      <FilterBlock v-model="listQuery" />
      <DownloadXlsxBtn :func="funcXlsx" />
      <q-space />
    </div>
    <ShowTable :list="list" :edit="edit" @reload="reload" class="q-pt-sm" />
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowTable from './components/ShowTable/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FiltersBlock/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import DownloadXlsxBtn from 'src/Modules/Files/components/DownloadXlsxFileBtn/index.vue'
import { getFullBalanceForStead, getFullBalanceForSteadXlsx } from 'src/Modules/Bookkeeping/api/balaceApi.js'
import BalanceForStead from 'src/Modules/Bookkeeping/components/Balance/BalanceForStead/index.vue'

export default defineComponent({
  components: {
    ShowTable,
    FilterBlock,
    BalanceForStead,
    LoadMore,
    DownloadXlsxBtn
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const func = getFullBalanceForStead
    const listQuery = ref({
      stead_id: props.steadId,
      rate_group_id: '',
      type: '',
      is_paid: '',
      date_start: '',
      date_end: '',
      page: 1,
      limit: 20
    })
    const funcXlsx = computed(() => {
      const tmp = Object.assign({}, listQuery.value)
      tmp.xlsx = 1
      return () => {
        return getFullBalanceForSteadXlsx(tmp)
      }
    })
    const authStore = useAuthStore()
    const edit = computed(() => {
      return authStore.permissions.includes('payment-edit')
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
      edit,
      reload,
      setList,
      func,
      funcXlsx,
      list
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
