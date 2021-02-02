<template>
  <div class="app-container">
    <div class="ma2" :class="{'text-red': stead.balans_all < 0}"><b>Участок № {{ stead.number }} размер {{ stead.size }} кв.м.</b></div>
    <div class="ma2" :class="{'text-red': stead.balans_all < 0}">Баланс:  <b>{{ stead.balans_all }} руб.</b></div>
    <el-tabs type="border-card">
      <el-tab-pane label="Платежи">
        <InvoiceAndPayment :key="key[1]" @reload="reload(1)" />
      </el-tab-pane>
      <el-tab-pane label="Показания">
        <InstrumentReadings :key="key[2]" @reload="reload(2)" />
      </el-tab-pane>
      <el-tab-pane label="Данные">
        <SteadInfo :key="key[3]" @reload="reload(3)" />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import waves from '@/directive/waves'
import InvoiceAndPayment from './components/InvoiceAndPayment'
import InstrumentReadings from './components/InstrumentReadings'
import SteadInfo from './components/SteadInfo'
// import { fetchBillingBalansSteadInfo } from '@/api/admin/billing'
import { fetchSteadInfo } from '@/api/admin/stead'

export default {
  directives: { waves },
  components: {
    InvoiceAndPayment,
    InstrumentReadings,
    SteadInfo
  },
  data() {
    return {
      key: {
        1: 1,
        2: 1,
        3: 1
      },
      key1: 1,
      key2: 1,
      key3: 1,
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
    reload(val) {
      [1, 2, 3].forEach(item => {
        if (item !== val) {
          this.key[item]++
        }
      })
    },
    getData() {
      fetchSteadInfo(this.id).then(response => {
        this.stead = response.data.data
        this.listLoading = false
      })
    }
  }
}
</script>

<style scoped>

</style>
