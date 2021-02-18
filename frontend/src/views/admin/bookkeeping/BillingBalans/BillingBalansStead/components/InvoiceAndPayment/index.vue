<template>
  <div>
    <div class="table-filter-header">
      <el-select
        v-model="listQuery.receiptType"
        placeholder="Назначение"
        clearable
        class="table-filter-header__item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in receiptTypes"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <el-select
        v-model="listQuery.type"
        placeholder="Счет, платеж"
        class="table-filter-header__item"
        @change="handleFilter"
      >
        <el-option label="Все" value="" />
        <el-option label="Счета" value="invoice" />
        <el-option label="Платежи" value="payment" />
      </el-select>
      <el-button type="primary" size="small" plain class="table-filter-header__item" @click="handleFilter">Показать</el-button>
      <el-button type="success" size="small" plain class="table-filter-header__item" @click="addInvoiceShow = true">Добавить счет</el-button>
    </div>
    <div class="billing-balans-stead">
      <el-table
        v-loading="listLoading"
        :data="list"
        border
        fit
        style="width: 100%"
        :row-class-name="tableRowClassName"
      >
        <el-table-column
          type="index"
          align="center"
          width="50"
        />
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
        <el-table-column align="center" label="Назначение">
          <template slot-scope="{ row }">
            <div v-if="row.data.description">
              <div class="relative dib pr2 pt2">
                <div class="absolute top-0 right-0 dark-red fw6">!</div>
                <el-popover
                  placement="top"
                  width="200"
                  trigger="click"
                  :content="row.data.description"
                >
                  <div slot="reference">
                    <div v-if="row.type == 'invoice'">
                      Счет: {{ row.data.title }}
                    </div>
                    <div v-if="row.type == 'payment'">
                      Оплата: {{ row.data.raw_data[4] }}
                    </div>
                  </div>
                </el-popover>
              </div>
            </div>
            <div v-if="!row.data.description">
              <div v-if="row.type == 'invoice'">
                Счет: {{ row.data.title }}
              </div>
              <div v-if="row.type == 'payment'">
                Оплата: {{ row.data.raw_data[4] }}
              </div>
            </div>
          </template>
        </el-table-column>
        <el-table-column v-for="item in receiptTypes" :key="item.id" align="center" :label="item.name" :prop="item.id.toString()" width="150px">
          <template slot-scope="{row}">
            <div>
              <span v-if="row.type =='payment' && row.data.type === item.id">{{ row.data.price | formatPrice }}</span>
              <span v-if="row.type =='invoice' && row.data.type === item.id">-{{ row.data.price | formatPrice }}</span>
            </div>

          </template>
        </el-table-column>
        <el-table-column label="Actions">
          <template slot-scope="{row}">
            <div>
              <el-button type="primary" size="small" icon="el-icon-info" @click="showMore(row)">
                Подробнее
              </el-button>
              <div v-if="row.data.type_depends" class="inst_read">
                <span v-for="d in row.data.depends" :key="d.id">
                  <span v-if="row.data.instr_read['d' + d.id]">
                    {{ row.data.instr_read['d' + d.id].value }}
                  </span>
                </span>
              </div>
            </div>
          </template>
        </el-table-column>
      </el-table>
      <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
      <PaymentInfo v-if="showPaymentInfo" :id="itemSelected.id" @close="closePaymentInfo" />
      <InvoiceInfo v-if="showInvoiceInfo" :id="itemSelected.id" @close="closeInvoiceInfo" />
      <el-dialog
        title="Добавить счет"
        :visible.sync="addInvoiceShow"
        top="10px"
        :width="mobile ? '100%' : '600px'"
      >
        <AddInvoiceForStead :stead="stead" @close="closeAddForm" />
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { fetchBillingBalansSteadInfo } from '@/api/admin/billing'
import waves from '@/directive/waves'
import PaymentInfo from '@/components/BillingPaymetnInfo'
import InvoiceInfo from '@/components/BillingInvoiceInfo'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'
import AddInvoiceForStead from '@/components/AddInvoiceForStead'
import LoadMore from '@/components/LoadMore'
import { fetchSteadInfo } from '@/api/admin/stead'

export default {
  components: { PaymentInfo, InvoiceInfo, AddInvoiceForStead, LoadMore },
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
  data() {
    return {
      key: 1,
      func: fetchBillingBalansSteadInfo,
      addInvoiceShow: false,
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
        stead_id: '',
        receiptType: '',
        type: '',
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
    },
    listWatch() {
      return JSON.stringify(this.listQuery)
    }
  },
  watch: {
    listWatch() {
      localStorage.setItem('steadInvoiceAndPaymentListQuery', JSON.stringify(this.listQuery))
    }
  },
  created() {
    this.listQuery = Object.assign({}, this.listQuery, JSON.parse(localStorage.getItem('steadInvoiceAndPaymentListQuery')))
    this.id = this.$route.params && this.$route.params.id
    this.listQuery.stead_id = this.id
    this.listQuery.page = 1
    this.getTypeList()
    this.getSteadInfo()
  },
  methods: {
    handleFilter() {
      this.listQuery.page = 1
      this.key++
      this.listLoading = true
    },
    setList(val) {
      // console.log('setliast')
      this.list = val
      this.listLoading = false
    },
    closeInvoiceInfo() {
      this.showInvoiceInfo = false
      this.showPaymentInfo = false
      this.key++
    },
    closeAddForm() {
      this.addInvoiceShow = false
      this.key++
      // this.getData()
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
      this.key++
    },
    showMore(row) {
      this.itemSelected = row.data
      // console.log(this.itemSelected)
      if (row.type === 'payment') {
        this.showInvoiceInfo = false
        this.showPaymentInfo = true
      }
      if (row.type === 'invoice') {
        this.showPaymentInfo = false
        this.showInvoiceInfo = true
        // console.log(this.showInvoiceInfo)
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
            if (column.property === item.data.type.toString()) {
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
    getSteadInfo() {
      fetchSteadInfo(this.id)
        .then(response => {
          if (response.data.status) {
            this.stead = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
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
        // this.stead = response.data.data.stead_info
        this.list = response.data.data.invoices
        this.listLoading = false
      })
    }
  }
}
</script>

<style scoped>
  .inst_read {
    font-size: 0.8em;
    margin-bottom: -5px;
    margin-top: -6px;
    color: blueviolet;
    display: inline-block;
  }
  .table-filter-header {
    display: flex;
    margin-bottom: 15px;
  }
  .table-filter-header .table-filter-header__item{
    margin-left: 0;
    margin-right: 10px;
  }
  .billing-balans-stead >>> .warning-row {
    background: #fff0f0;
  }
  .billing-balans-stead >>> .success-row {
    background: #f0fff0;
  }

</style>
