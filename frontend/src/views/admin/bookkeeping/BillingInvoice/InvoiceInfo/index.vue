<template>
  <div v-if="load" class="app-container">
    Детали счета № {{ id }} от {{ invoice.created_at | moment('DD-MM-YYYY') }}
    <div>
      Счет на: {{invoice.title}}
    </div>
    <div>
      Участок: <el-button size="mini" type="warning" plain @click="showSteadBalans">{{invoice.stead_number}}</el-button>
    </div>
    <div>
      Сумма: {{invoice.price}} руб.
    </div>
    <div>
      Оплата:
      <el-tag type="danger" :effect="invoice.type | type1EffectFilter">
        <i v-if="invoice.type == 1" class="el-icon-check"></i>
        Электоэнергия
      </el-tag>
      <el-tag type="success" :effect="invoice.type | type2EffectFilter">
        <i v-if="invoice.type == 2" class="el-icon-check"></i>
        Взносы
      </el-tag>
    </div>
   </div>
</template>

<script>
import { fetchInvoiceInfo } from '@/api/admin/bookkeping/invoice'

export default {
  filters: {
    type1EffectFilter(val) {
      if (val === 1) {
        return 'dark'
      }
      return 'plain'
    },
    type2EffectFilter(val) {
      if (val === 2) {
        return 'dark'
      }
      return 'plain'
    },
  },
  components: {
  },
  data() {
    return {
      id: null,
      load: false,
      invoice: {},
    }
  },
  created() {
    this.id = this.$route.params && this.$route.params.id
    this.getData()
  },
  methods: {
    showSteadBalans() {
      this.$router.push('/bookkeping/billing_balance_stead/' + this.invoice.stead_id)
    },
    getData() {
      this.load = false
      fetchInvoiceInfo(this.id).then(response => {
        if (response.data.status) {
          this.invoice = response.data.data
          this.load = true
        }
      })
    },
  }
}
</script>

<style scoped>

</style>
