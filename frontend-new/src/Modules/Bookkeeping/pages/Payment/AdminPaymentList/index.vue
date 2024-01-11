<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-sm">
      <FilterBlock v-model="listQuery" />
      <div>
        <q-btn label="Excel" color="primary" @click="alert('ой')" />
      </div>
      <q-space />
      <div>
        <q-btn label="Добавить" color="primary" to="/admin/bookkeeping/payment/add" />
      </div>
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
import FilterBlock from './components/FiltersBlock/index.vue'
import { getPaymentList } from 'src/Modules/Bookkeeping/api/paymentApi.js'

export default defineComponent({
  components: {
    ShowTable,
    FilterBlock,
    LoadMore
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const func = getPaymentList
    const listQuery = ref({
      find: '',
      rate_group_id: '',
      is_error: '',
      date_start: '',
      date_end: '',
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
