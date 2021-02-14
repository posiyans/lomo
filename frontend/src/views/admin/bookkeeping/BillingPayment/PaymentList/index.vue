<template>
  <div class="app-container">
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
          @keyup.enter.native="handleFilter"
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
        <el-button v-waves class="filter-container__item" type="primary" icon="el-icon-search" @click="handleFilter">
          Показать
        </el-button>
        <el-button v-waves class="filter-container__item" type="success" @click="addPayment">Добавить выписку из банка</el-button>
      </div>
      <component :is="componentName" :list="list" @reload="key++" />
      <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
    </div>
    <AddPayment v-if="show === 1" @close="closeAddPaymentForm" @showPayment="showPayment" />
    <ShowNewPayment v-if="show === 2" :list="newList" @close="closeShowPaymentForm" />
  </div>

</template>

<script>
import { fetchPaymentList } from '@/api/admin/bookkeping/payment'
import { parseTime } from '@/utils'
import waves from '@/directive/waves'
import LoadMore from '@/components/LoadMore'
import Desktop from './Table/Desktop'
// import Mobile from './Table/Mobile'
import Mobile from './Table/Desktop'
import AddPayment from './component/AddPayment'
import ShowNewPayment from './component/ShowNewPayment'

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
    }
  },
  components: {
    LoadMore,
    Desktop,
    Mobile,
    AddPayment,
    ShowNewPayment
  },
  directives: { waves },
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
  computed: {
    mobile() {
      if (this.$store.state.app.device === 'mobile') {
        return true
      }
      return false
    },
    componentName() {
      if (this.mobile) {
        return Mobile
      }
      return Desktop
    }
  },
  created() {
    this.handleFilter()
  },
  methods: {
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
      this.show = 1
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
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['timestamp', 'title', 'type', 'importance', 'status']
        const filterVal = ['timestamp', 'title', 'type', 'importance', 'status']
        const data = this.formatJson(filterVal)
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'table-list'
        })
        this.downloadLoading = false
      })
    },
    formatJson(filterVal) {
      return this.list.map(v => filterVal.map(j => {
        if (j === 'timestamp') {
          return parseTime(v[j])
        } else {
          return v[j]
        }
      }))
    }
  }
}
</script>

<style scoped>

</style>
