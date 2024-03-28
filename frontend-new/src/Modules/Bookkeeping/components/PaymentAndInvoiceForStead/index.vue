<template>
  <div>
    <BalanceForStead :stead-id="steadId" class="q-pa-sm" />
    <div class="row q-col-gutter-sm q-pb-xs no-wrap">
      <DropDownBlock only-mobile>
        <FilterBlock v-model="listQuery" />
      </DropDownBlock>
      <q-space />
      <q-fab
        flat
        text-color="black"
        icon="more_vert"
        vertical-actions-align="right"
        direction="down"
      >
        <DownloadXlsxBtn :func="funcXlsx">
          <q-fab-action label-position="left" color="primary" icon="download" label="Скачать" anchor="end" />
        </DownloadXlsxBtn>
        <AddInvoiceBtn v-if="showInvoiceAddBtn" :stead-id="steadId" @success="reload">
          <q-fab-action label-position="left" color="red" icon="add_shopping_cart" label="Добавить счет" />
        </AddInvoiceBtn>
        <AddPaymentBtn v-if="showPaymentAddBtn" :stead-id="steadId" @success="reload">
          <q-fab-action label-position="left" color="teal" icon="payment" label="Добавить платеж" />
        </AddPaymentBtn>
      </q-fab>
    </div>
    <ShowTable :list="list" @reload="reload" />
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowTable from './components/ShowTable/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FiltersBlock/index.vue'
import DownloadXlsxBtn from 'src/Modules/Files/components/DownloadXlsxFileBtn/index.vue'
import { getFullBalanceForStead, getFullBalanceForSteadXlsx } from 'src/Modules/Bookkeeping/api/balaceApi.js'
import BalanceForStead from 'src/Modules/Bookkeeping/components/Balance/BalanceForStead/index.vue'
import DropDownBlock from 'components/DropDownBlock/index.vue'
import AddInvoiceBtn from 'src/Modules/Bookkeeping/components/Invoice/AddInvoiceBtn/index.vue'
import AddPaymentBtn from 'src/Modules/Bookkeeping/components/Payment/AddPaymentBtn/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ShowTable,
    DropDownBlock,
    FilterBlock,
    BalanceForStead,
    LoadMore,
    DownloadXlsxBtn,
    AddInvoiceBtn,
    AddPaymentBtn
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
    const showInvoiceAddBtn = computed(() => {
      return authStore.checkPermission(['invoice-edit'])
    })
    const showPaymentAddBtn = computed(() => {
      return authStore.checkPermission(['payment-edit'])
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
      funcXlsx,
      list,
      showInvoiceAddBtn,
      showPaymentAddBtn
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
