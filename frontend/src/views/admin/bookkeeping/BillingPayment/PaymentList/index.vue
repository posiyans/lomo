<template>
  <div class="app-container">
    <div class="ma2"><b>Платежи садоводов</b></div>
    <div class="filter-container">
      <el-input v-model="listQuery.find" placeholder="Найти сумму или участок" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-date-picker
        v-model="listQuery.date"
        type="daterange"
        range-separator="по"
        class="filter-item"
        format="dd-MM-yyyy"
        value-format="yyyy-MM-dd"
        start-placeholder="с даты"
        end-placeholder="по дату"
      />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-if="false" v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        Export
      </el-button>
    </div>
    <component :is="componentName" :list="list" />
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
  </div>

</template>

<script>
import { fetchPaymentList } from '@/api/admin/bookkeping/payment'
import { parseTime } from '@/utils'
import waves from '@/directive/waves'
import LoadMore from '@/components/LoadMore'
import Desktop from './Table/Desktop'
import Mobile from './Table/Mobile'

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
    Mobile
  },
  directives: { waves },
  data() {
    return {
      key: 1,
      listQuery: {
        page: 1,
        limit: 20,
        find: '',
        date: []
      },
      list: [],
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
    setList(val) {
      console.log('setlist')
      this.list = val
      this.listLoading = false
    },
    handleFilter() {
      this.listQuery.page = 1
      this.key++
      this.listLoading = true
    },
    // changeStead(data) {
    //   this.$confirm('ВЫ действительно хотите сменить участок для платежа?', 'Внимание!!!', {
    //     confirmButtonText: 'Да',
    //     cancelButtonText: 'Нет',
    //     type: 'error'
    //   }).then(() => {
    //     this.payment.stead_id = this.newStead.id
    //     this.payment.stead_number = this.newStead.number
    //     this.changeSteadVizible = false
    //   }).catch(() => {
    //     this.changeSteadVizible = false
    //   })
    // },
    // setNewStead(data) {
    //   this.newStead = data
    // },
    // showChangeStead() {
    //   this.changeSteadVizible = true
    // },
    getData() {
      // this.load = false
      fetchPaymentList(this.listQuery).then(response => {
        if (response.data.status) {
          this.list = response.data.data
          // this.load = true
        }
      })
    },
    // selectElect() {
    //   console.log('fsdfsdfs')
    //   this.payment.type = 1
    //   this.dialogMeterReadingFormVisible = true
    // },
    // closeMetering() {
    //   this.dialogMeterReadingFormVisible = false
    // },
    // setMeterReading(val) {
    //   if (val[0]) {
    //     this.payment.raw_data.meterReading1 = val[0]
    //   }
    //   if (val[1]) {
    //     this.payment.raw_data.meterReading2 = val[1]
    //   }
    // },
    // savePayment() {
    //   updatePaymentInfo(this.payment.id, this.payment).then(response => {
    //     if (response.data.status) {
    //       this.$message('Данные сохранены')
    //     } else {
    //       this.$message.error('Ошибка при сохранении')
    //     }
    //   })
    // }
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
