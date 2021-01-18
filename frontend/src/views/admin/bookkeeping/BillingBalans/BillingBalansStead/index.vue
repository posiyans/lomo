<template>
  <div class="app-container">
    <div class="ma2" :class="{'text-red': stead.balans < 0}"><b>Участок № {{ stead.number }} размер {{ stead.size }} кв.м.</b></div>
    <div class="ma2" :class="{'text-red': stead.balans < 0}">Баланс:  <b>{{ stead.balans }} руб.</b></div>
    <el-tabs type="border-card">
      <el-tab-pane label="Платежи">
        <InvoiceAndPayment :key="key" @reload="key++" />
      </el-tab-pane>
      <el-tab-pane label="Показания">
        <InstrumentReadings :key="key" @reload="key++" />
      </el-tab-pane>
      <el-tab-pane label="Данные">
        <SteadInfo :key="key" @reload="key++" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import waves from '@/directive/waves'
import InvoiceAndPayment from './components/InvoiceAndPayment'
import InstrumentReadings from './components/InstrumentReadings'
import SteadInfo from './components/SteadInfo'
import { fetchBillingBalansSteadInfo } from '@/api/admin/billing'

export default {
  // components: { Pagination },
  directives: { waves },
  components: {
    InvoiceAndPayment,
    InstrumentReadings,
    SteadInfo
  },
  data() {
    return {
      key: 1,
      stead: '',
      id: 0
    }
  },
  computed: {
    mobile() {
      return this.$store.state.app.device === 'mobile'
    }
  },
  mounted() {
    this.id = this.$route.params && this.$route.params.id
    this.getData()
  },
  methods: {
    getData() {
      fetchBillingBalansSteadInfo({ stead_id: this.id }).then(response => {
        this.stead = response.data.data.stead_info
        this.listLoading = false
      })
    }
  }
}
</script>

<style scoped>

</style>
