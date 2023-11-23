<template>
  <div class="q-pa-md">
    <UserSteadFind :stead-id="id" @selectStead="changeStead" />
    <div class="" :class="{'text-red': stead.balans_all < 0}"><b>Участок № {{ stead.number }} размер {{ stead.size }} кв.м.</b></div>
    <div class="" :class="{'text-red': balans.balans_all < 0, 'dark-green': balans.balans_all >= 0}">Баланс: <b>{{ balans.balans_all }} руб.</b></div>
    <div v-for="item in balans.balans" :key="item.name" class="" :class="{'text-red': item.price < 0, 'dark-green': item.price >= 0}">{{ item.name }}: <b>{{ item.price }} руб.</b></div>
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
import InvoiceAndPayment from './components/InvoiceAndPayment'
import InstrumentReadings from './components/InstrumentReadings'
import SteadInfo from './components/SteadInfo'
import { fetchBillingAllBalansForStead } from 'src/Modules/Bookkeeping/api/billingAminApi.js'
import { fetchSteadInfo } from 'src/Modules/Stead/api/steadAdminApi.js'
import UserSteadFind from 'src/Modules/Stead/components/UserSteadFind/index.vue'

export default {
  components: {
    InvoiceAndPayment,
    InstrumentReadings,
    SteadInfo,
    UserSteadFind
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
      balans: {},
      id: 0
    }
  },
  computed: {
    mobile() {
      return this.$q.platform.is.mobile
    }
  },
  mounted() {
    this.id = this.$route.params && this.$route.params.id
    this.getData()
    this.getBalans()
  },
  methods: {
    changeStead(val) {
      this.$router.push('/admin/bookkeeping/billing_balance_stead/' + val.id)
    },
    reload(val) {
      [1, 2, 3].forEach(item => {
        if (item !== val) {
          this.key[item]++
        }
      })
      this.getBalans()
    },
    getBalans() {
      fetchBillingAllBalansForStead({ id: this.id }).then(response => {
        this.balans = response.data.data
        this.listLoading = false
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
