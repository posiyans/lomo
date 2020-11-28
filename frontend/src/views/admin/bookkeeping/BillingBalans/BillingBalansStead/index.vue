<template>
  <div class="app-container">
    <div class="ma2" :class="{'text-red': stead.balans < 0}"><b>Счета и платежи по участку № {{ stead.number }} размер {{ stead.size }} кв.м.</b></div>
    <div class="ma2" :class="{'text-red': stead.balans < 0}">Баланс:  <b>{{ stead.balans }} руб.</b></div>
    <div class="billing-balans-stead">
      <el-table
        v-loading="listLoading"
        :data="list"
        border
        fit
        highlight-current-row
        style="width: 100%"
        :row-class-name="tableRowClassName"
        :summary-method="getSummaries"
        show-summary
      >
        <el-table-column label="Дата" width="100px">
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
              Оплата: {{ row.data.discription }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Дебет" width="100px" prop="invoice" align="center">
          <template slot-scope="{row}">
            <div v-if="row.type == 'invoice'">
              <span>{{ row.data.price }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Кредит" width="100px" prop="payment" align="center">
          <template slot-scope="{row}">
            <div v-if="row.type == 'payment'">
              <span>{{ row.data.price }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Взносы" width="100px" prop="payment2" align="center">
          <template slot-scope="{row}">
            <div v-if="row.type == 'payment' && row.data.type == 2">
              <span>{{ row.data.price }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Элект-во" prop="payment1" width="100px" align="center">
          <template slot-scope="{row}">
            <div v-if="row.type == 'payment' && row.data.type == 1">
              <span>{{ row.data.price }}</span>
            </div>
          </template>
        </el-table-column>
        <el-table-column align="center" label="Actions">
          <template slot-scope="{row}">
            <el-button type="primary" size="small" icon="el-icon-edit" @click="showMore(row)">
              Подробнее
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-dialog title="Детали платежа" :visible.sync="dialogPaymentInfoVisible">
        <div v-if="rowShow.data">
          <div>
            Дата оплаты: {{ rowShow.data.payment_date }}
          </div>
          <div>
            Назначение платежа: {{ rowShow.data.discription }}
          </div>
          <div>
            ФИО: {{ rowShow.data.raw_data.val6 }}
          </div>
          <div>
            Сумма: {{ rowShow.data.price }} руб.
          </div>
          <div>
            Оплата:
            <el-tag type="danger" :effect="rowShow.data.type | type1EffectFilter" @click="selectElect()">
              <i v-if="rowShow.data.type == 1" class="el-icon-check" />
              Электоэнергия
            </el-tag>
            <el-tag type="success" :effect="rowShow.data.type | type2EffectFilter" @click="rowShow.data.type = 2">
              <i v-if="rowShow.data.type == 2" class="el-icon-check" />
              Взносы
            </el-tag>
            <div v-if="rowShow.data.type == 1">
              <div v-if="rowShow.data.meterReading1" class="text-red mt2 mb2">
                Показания день: <b>{{ rowShow.data.meterReading1 }}</b> кв*ч
              </div>
              <div v-if="rowShow.data.meterReading2" class="text-green mt2 mb2">
                Показания ночь: <b>{{ rowShow.data.meterReading2 }}</b> кв*ч
              </div>
            </div>
          </div>
          <div>
            Участок:
          </div>
          {{ rowShow }}
        </div>
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogPaymentInfoVisible = false">Закрыть</el-button>
          <el-button type="primary" @click="savePaymenInfo">Сохранить</el-button>
        </span>
      </el-dialog>
      <div v-if="dialogMeterReadingFormVisible">
        <el-dialog title="Уточнить участок" :visible.sync="dialogMeterReadingFormVisible">
          <div class="mb2">{{ rowShow.data.discription }}</div>
          <el-form label-position="top">
            <el-form-item label="Показания день">
              <el-input
                v-model="rowShow.data.meterReading1"
                placeholder="Показания день"
                prefix-icon="el-icon-search"
              />
            </el-form-item>
            <el-form-item label="Показания ночь">
              <el-input
                v-model="rowShow.data.meterReading2"
                placeholder="Показания ночь"
                prefix-icon="el-icon-search"
              />
            </el-form-item>
          </el-form>
          <span slot="footer" class="dialog-footer">
            <el-button type="primary" @click="dialogMeterReadingFormVisible = false">Ок</el-button>
          </span>
        </el-dialog>
      </div>
    </div>
    <!--    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />-->
  </div>
</template>

<script>
import { fetchBillingBalansSteadInfo } from '@/api/admin/billing'
import { updatePaymentInfo } from '@/api/admin/bookkeping/payment'
// import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import waves from '@/directive/waves'

export default {
  // components: { Pagination },
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
      id: '',
      stead: '',
      list: [],
      total: 0,
      rowShow: {},
      listLoading: true,
      dialogPaymentInfoVisible: false,
      dialogMeterReadingFormVisible: false,
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
      if (this.$store.state.app.device === 'mobile') {
        return true
      }
      return false
    }
  },
  created() {
    this.id = this.$route.params && this.$route.params.id
    this.getData()
  },
  methods: {
    showMore(row) {
      if (row.type === 'payment') {
        this.$router.push('/bookkeping/payment_info/' + row.data.id)
      }
      if (row.type === 'invoice') {
        this.$router.push('/bookkeping/invoice_info/' + row.data.id)
      }
    },
    savePaymenInfo() {
      this.dialogPaymentInfoVisible = false
      updatePaymentInfo(this.rowShow.id, this.rowShow)
    },
    selectElect(row) {
      this.rowShow.data.type = 1
      // this.editRow = row
      this.dialogMeterReadingFormVisible = true
    },
    showPayment(row) {
      this.rowShow = row
      this.dialogPaymentInfoVisible = true
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
            if (column.property === 'payment1' && item.type === 'payment' && item.data.type === 1) {
              return item.data.price
            }
            if (column.property === 'payment2' && item.type === 'payment' && item.data.type === 2) {
              return item.data.price
            }
            if (item.type === column.property) {
              return item.data.price
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
