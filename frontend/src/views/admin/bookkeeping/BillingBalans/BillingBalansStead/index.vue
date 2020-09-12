<template>
  <div class="app-container">
    <div class="ma2" :class="{'text-red': stead.balans < 0}"><b>Счета и платежи по участку № {{stead.number}} размер {{stead.size}} кв.м.</b></div>
    <div class="ma2" :class="{'text-red': stead.balans < 0}">Баланс:  <b>{{stead.balans}} руб.</b></div>
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
      <el-table-column label="Дата">
        <template slot-scope="{row}">
          <div v-if="row.type == 'invoice'">
            <span>{{ row.data.created_at | moment('DD-MM-YYYY')}}</span>
          </div>
          <div v-if="row.type == 'payment'">
            <span>{{ row.data.payment_date | moment('DD-MM-YYYY')}}</span>
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
      <el-table-column label="Дебет" prop="invoice" align="center">
        <template slot-scope="{row}">
          <div v-if="row.type == 'invoice'">
            <span>{{ row.data.price}}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Кредит" prop="payment" align="center">
        <template slot-scope="{row}">
          <div v-if="row.type == 'payment'">
            <span>{{ row.data.price }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Actions" >
        <template slot-scope="scope">
          <router-link :to="'/bookkeping/billing_balance_stead/'+scope.row.id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              Подробнее
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>
</div>
<!--    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />-->
  </div>
</template>

<script>
import { fetchAdminArticleList } from '@/api/article'
import { fetchBillingBalansSteadInfo } from '@/api/admin/billing'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import { fetchCategoryList } from '@/api/category'
import waves from '@/directive/waves'
import {mapState} from "vuex"; // waves directive

export default {
  components: { Pagination },
  directives: { waves },
  filters: {
  },
  props: {
  },
  data() {
    return {
      id: '',
      stead: '',
      list: [],
      total: 0,
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
    },
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
