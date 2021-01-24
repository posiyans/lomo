<template>
  <div>
    <el-button type="primary" size="small" plain>Добавить счет</el-button>
    <div class="billing-balans-stead">
      <el-table
        v-loading="listLoading"
        :data="list"
        border
        fit
        style="width: 100%"
        :row-class-name="tableRowClassName"
        :summary-method="getSummaries"
        show-summary
      >
        <el-table-column label="Дата" align="center" width="100px">
          <template slot-scope="{row}">
            <div v-if="row.type == 'invoice'">
              <span>{{ row.data.created_at | moment('DD-MM-YYYY') }}</span>
            </div>
            <div v-if="row.type == 'payment'">
              <span>{{ row.data.payment_date | moment('DD-MM-YYYY') }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column align="center" label="Название">
          <template slot-scope="{ row }">
            <div v-if="row.type == 'invoice'">
              Счет: {{ row.data.title }}
            </div>
            <div v-if="row.type == 'payment'">
              Оплата: {{ row.data.raw_data[4] }}
            </div>
          </template>
        </el-table-column>
        <el-table-column v-for="item in receiptTypes" :key="item.id" align="center" :label="item.name" :prop="item.id" width="150px">
          <template slot-scope="{row}">
            <span v-if="row.type =='payment' && row.data.type === item.id">{{ row.data.price | formatPrice }}</span>
            <span v-if="row.type =='invoice' && row.data.type === item.id">-{{ row.data.price | formatPrice }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="Actions">
          <template slot-scope="{row}">
            <el-button type="primary" size="small" icon="el-icon-info" @click="showMore(row)">
              Подробнее
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <PaymentInfo v-if="showPaymentInfo" :payment_id="itemSelected.id" @close="closePaymentInfo" />
      <InvoiceInfo v-if="showInvoiceInfo" :invoice_id="itemSelected.id" @close="closeInvoiceInfo" />
    </div>
  </div>
</template>

<script>
import { fetchBillingBalansSteadInfo } from '@/api/admin/billing'
import waves from '@/directive/waves'
import PaymentInfo from '@/components/BillingPaymetnInfo'
import InvoiceInfo from '@/components/BillingInvoiceInfo'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'

export default {
  components: { PaymentInfo, InvoiceInfo },
  directives: { waves },
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
    }
  },
  props: {
  },
  data() {
    return {
      receiptTypes: [],
      showInvoiceInfo: false,
      showPaymentInfo: false,
      itemSelected: '',
      id: '',
      stead: '',
      list: [],
      total: 0,
      rowShow: {},
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        find: null,
        status: null,
        category: null
      }
    }
  },
  computed: {
    mobile() {
      return this.$store.state.app.device === 'mobile'
    }
  },
  created() {
    this.id = this.$route.params && this.$route.params.id
    this.getData()
    this.getTypeList()
  },
  methods: {
    closeInvoiceInfo() {
      this.showInvoiceInfo = false
      this.showPaymentInfo = false
    },
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receiptTypes = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    closePaymentInfo() {
      this.showPaymentInfo = false
      this.$emit('reload')
    },
    showMore(row) {
      this.itemSelected = row.data
      if (row.type === 'payment') {
        this.showInvoiceInfo = false
        this.showPaymentInfo = true
      }
      if (row.type === 'invoice') {
        this.showPaymentInfo = false
        this.showInvoiceInfo = true
        console.log(this.showInvoiceInfo)
      }
      // if (row.type === 'invoice') {
      //   this.$router.push('/bookkeping/invoice_info/' + row.data.id)
      // }
    },
    getSummaries(param) {
      const { columns, data } = param
      const sums = []
      columns.forEach((column, index) => {
        if (index === 0) {
          sums[index] = 'Итого'
          return
        }
        if (column.property) {
          const values = data.map(item => {
            // if (column.property === 'payment1' && item.type === 'payment' && item.data.type === 1) {
            //   return item.data.price
            // }
            // if (column.property === 'payment2' && item.type === 'payment' && item.data.type === 2) {
            //   return item.data.price
            // }
            if (column.property === item.data.type) {
              if (item.type === 'payment') {
                return item.data.price
              }
              return -item.data.price
            }
            return 0
          })
          if (!values.every(value => isNaN(value))) {
            sums[index] = values.reduce((prev, curr) => {
              const value = Number(curr)
              if (!isNaN(value)) {
                return prev + curr
              } else {
                return prev
              }
            }, 0)
          } else {
            sums[index] = 'N/A'
          }
        }
      })
      return sums
    },
    tableRowClassName({ row, rowIndex }) {
      if (row.type === 'invoice') {
        return 'warning-row'
      } else if (row.type === 'payment') {
        return 'success-row'
      }
      return ''
    },
    getData() {
      this.listLoading = true
      fetchBillingBalansSteadInfo({ stead_id: this.id }).then(response => {
        this.stead = response.data.data.stead_info
        this.list = response.data.data.invoices
        this.listLoading = false
      })
    }
  }
}
</script>

<style scoped>
.billing-balans-stead >>> .warning-row {
  background: #fff0f0;
}
.billing-balans-stead >>> .success-row {
  background: #f0fff0;
}

</style>
