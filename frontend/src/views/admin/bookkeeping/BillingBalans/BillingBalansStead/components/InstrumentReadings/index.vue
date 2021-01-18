<template>
  <div class="app-container">
    <div class="filter-container">

      <el-select
        v-model="listQuery.primaryType"
        placeholder="Тип прибора"
        @change="getItemsTypeList"
      >
        <el-option
          v-for="item in typeList"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <el-select
        v-model="listQuery.type_id"
        :disabled="!listQuery.primaryType"
        placeholder="Тип прибора"
        clearable
        @change="getList"
      >
        <el-option
          v-for="item in itemsTypeList"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
      <el-button type="success" class="item-filter" plain @click="addReadings">Добавить показания</el-button>
    </div>
    <el-table
      v-if="list.length > 0"
      :data="list"
      border
      show-summary
      :summary-method="getSummaries"
      style="width: 100%"
    >
      <el-table-column
        label="Дата"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label=""
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.type_name }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Показания"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.value }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Разница"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.delta }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Тариф"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Сумма"
        prop="summa"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.summa.toFixed(2) }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label=""
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.payment_id }}</span>
        </template>
      </el-table-column>
    </el-table>
    <AddReadingDialog
      v-if="addReadingDialogShow"
      :stead_id="id"
      @close="closeReadingsDialog"
    />
  </div>
</template>

<script>
import { fetchCommunalListForStead } from '@/api/admin/bookkeping/communal'
import { fetchReceiptTypeList, getReceiptTypeInfo } from '@/api/admin/setting/receipt'
import AddReadingDialog from './components/AddReadingsDialog'

export default {
  components: { AddReadingDialog },
  data() {
    return {
      addReadingDialogShow: false,
      list: [],
      id: '',
      old1: 39100,
      old2: 16600,
      summa: 0,
      itemsTypeList: [],
      primaryType: '',
      typeList: [],
      listQuery: {
        primaryType: '',
        type_id: ''
      }
    }
  },
  mounted() {
    this.id = this.$route.params && this.$route.params.id
    this.getList()
    this.getTypeList()
  },
  methods: {
    addReadings() {
      this.addReadingDialogShow = true
    },
    closeReadingsDialog() {
      this.addReadingDialogShow = false
    },
    getSummaries(param) {
      const { columns, data } = param
      const sums = []
      columns.forEach((column, index) => {
        if (index === 0) {
          sums[index] = 'Итого'
          return
        }
        if (column.property === 'summa') {
          const values = data.map(item => {
            return item.summa
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
      sums[5] = sums[5].toFixed(2)
      return sums
    },
    getList() {
      fetchCommunalListForStead(this.id, this.listQuery)
        .then(response => {
          if (response.data.status) {
            this.list = []
            this.summa = 0
            response.data.data.forEach(item => {
              if (item.device_id === 1) {
                item.delta = item.value - this.old1
                item.summa = item.delta * item.price
                this.summa += item.summa
                this.list.push(item)
                this.old1 = +item.value
              }
              if (item.device_id === 2) {
                item.delta = item.value - this.old2
                item.summa = item.delta * item.price
                this.summa += item.summa
                this.list.push(item)
                this.old2 = +item.value
              }
            })
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.typeList = []
            response.data.data.forEach(item => {
              if (item.depends === 2) {
                this.typeList.push(item)
              }
            })
            if (this.typeList.length === 1) {
              this.listQuery.primaryType = this.typeList[0].id
              this.getItemsTypeList()
            }
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    getItemsTypeList() {
      this.getList()
      console.log('getItemsTypeList')
      getReceiptTypeInfo(this.listQuery.primaryType)
        .then(response => {
          if (response.data.status) {
            this.itemsTypeList = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    }
  }
}
</script>

<style scoped>

</style>
