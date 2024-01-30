<template>
  <div>
    <ShowTable :list="list" :columns="columns" :dense="dense" @selected="selected" />
    <div v-if="noList" class="text-center q-pa-sm text-weight-bold">
      Платежи не найдены
    </div>
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" class="q-px-xs" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import ShowTable from './components/ShowTable/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import { getPaymentList } from 'src/Modules/Bookkeeping/api/paymentApi.js'

export default defineComponent({
  components: {
    ShowTable,
    LoadMore
  },
  props: {
    steadId: {
      type: [String, Number],
      default: ''
    },
    rateGroupId: {
      type: [String, Number],
      default: ''
    },
    isInvoice: {
      type: [String, Number],
      default: ''
    },
    dense: {
      type: Boolean,
      default: false
    },
    columns: {
      type: Array,
      default: () =>
        [
          {
            name: 'id',
            align: 'center',
            label: '№'
          },
          {
            name: 'date',
            align: 'center',
            label: 'Дата'
          },
          {
            name: 'price',
            align: 'center',
            label: 'Сумма, руб'
          },

          {
            name: 'title',
            align: 'left',
            label: 'Назначение'
          },
          {
            name: 'stead',
            align: 'center',
            label: 'Участок'
          },
          {
            name: 'group',
            align: 'center',
            label: 'Группа'
          },
          {
            name: 'more',
            align: 'center',
            label: ''
          }
        ]

    }
  },
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const noList = ref(false)
    const func = getPaymentList
    const listQuery = ref({
      find: '',
      is_invoice: props.isInvoice, // есть у платежа счет (2) или нет (1)
      stead_id: props.steadId,
      rate_group_id: props.rateGroupId,
      is_error: '',
      date_start: '',
      date_end: '',
      page: 1,
      limit: 5
    })
    const reload = () => {
      key.value++
    }
    const setList = (val) => {
      list.value = val
      noList.value = list.value.length === 0
    }
    const selected = (data) => {
      emit('selected', data)
    }
    return {
      listQuery,
      selected,
      key,
      reload,
      setList,
      func,
      list,
      noList
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
