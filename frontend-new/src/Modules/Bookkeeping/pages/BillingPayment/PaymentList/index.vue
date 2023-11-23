<template>
  <div class="q-pa-md">
    <div v-if="show === 0">
      <div class="page-title">Платежи</div>
      <div class="filter-container">
        <el-input
          v-model="listQuery.find"
          placeholder="Найти сумму, участок, дату"
          style="width: 200px;"
          clearable
          class="filter-container__item"
          @clear="handleFilter"
          @keyup.enter="handleFilter"
        />
        <el-select
          v-model="listQuery.status"
          placeholder="Статус"
          class="filter-container__item"
          clearable
          @change="handleFilter"
        >
          <el-option label="Все" value="" />
          <el-option label="Только с ошибками" value="1" />
          <el-option label="Только без ошибок" value="2" />
        </el-select>
        <el-date-picker
          v-model="listQuery.date"
          type="daterange"
          range-separator="по"
          :picker-options="{ firstDayOfWeek: 1}"
          class="filter-container__item"
          format="dd-MM-yyyy"
          value-format="yyyy-MM-dd"
          start-placeholder="с даты"
          end-placeholder="по дату"
        />
        <el-button class="filter-container__item" type="primary" icon="el-icon-search" @click="handleFilter">
          Показать
        </el-button>
        <el-button class="filter-container__item" type="primary" icon="el-icon-search" @click="uploadFile">
          EXCEL
        </el-button>
        <el-button class="filter-container__item" type="success" @click="addPayment">Добавить выписку из банка</el-button>
      </div>
      <Desktop :list="list" @reload="key++" />
      <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
    </div>
    <AddPayment v-if="show === 1" @close="closeAddPaymentForm" @showPayment="showPayment" />
    <ShowNewPayment v-if="show === 2" :list="newList" @close="closeShowPaymentForm" />
  </div>

</template>

<script>
import { fetchPaymentList, fetchPaymentListinFile } from 'src/Modules/Bookkeeping/api/paymentAdminApi.js'
import LoadMore from 'src/components/LoadMore/index.vue'
import Desktop from './Table/Desktop'
import AddPayment from 'src/Modules/Bookkeeping/pages/BillingPayment/PaymentAdd/index.vue'
import ShowNewPayment from './component/ShowNewPayment'
import { exportFile } from 'quasar'

export default {
  components: {
    LoadMore,
    Desktop,
    AddPayment,
    ShowNewPayment
  },
  data() {
    return {
      key: 1,
      show: 0,
      listQuery: {
        status: '',
        page: 1,
        limit: 20,
        find: '',
        date: []
      },
      list: [],
      newList: [],
      func: fetchPaymentList,
      payment: {}

    }
  },
  created() {
    this.handleFilter()
  },
  methods: {
    uploadFile() {
      this.$message('скачиваем')
      const data = Object.assign({}, this.listQuery)
      data.file = 'xlsx'
      delete data.page
      delete data.limit
      fetchPaymentListinFile(data).then(response => {
        const blob = new Blob([response.data])
        exportFile('Список.xlsx', blob)
        this.$message('Файл успешно скачан.')
      })
    },
    closeShowPaymentForm() {
      this.show = 0
      this.newList = []
      this.listQuery.page = 1
      this.key++
    },
    showPayment(data) {
      this.newList = data
      this.show = 2
    },
    closeAddPaymentForm() {
      this.show = 0
    },
    addPayment() {
      this.$router.push('/admin/bookkeeping/payment_add')
    },
    setList(val) {
      this.list = val
      this.listLoading = false
    },
    handleFilter() {
      this.listQuery.page = 1
      this.key++
      this.listLoading = true
    },
    getData() {
      // this.load = false
      fetchPaymentList(this.listQuery).then(response => {
        if (response.data.status) {
          this.list = response.data.data
          // this.load = true
        }
      })
    },
    handleDownload() {
      // this.downloadLoading = true
      // import('@/vendor/Export2Excel').then(excel => {
      //   const tHeader = ['timestamp', 'title', 'type', 'importance', 'status']
      //   const filterVal = ['timestamp', 'title', 'type', 'importance', 'status']
      //   const data = this.formatJson(filterVal)
      //   excel.export_json_to_excel({
      //     header: tHeader,
      //     data,
      //     filename: 'table-list'
      //   })
      //   this.downloadLoading = false
      // })
    },
    formatJson(filterVal) {
      // return this.list.map(v => filterVal.map(j => {
      //   if (j === 'timestamp') {
      //     return parseTime(v[j])
      //   } else {
      //     return v[j]
      //   }
      // }))
    }
  }
}
</script>

<style scoped>

</style>
