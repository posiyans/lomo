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
    <!--    <div class="scroll-block">-->

    <el-table
      v-if="list.length > 0"
      :data="list"
      border
      style="width: 100%"
      @selection-change="handleSelectionChange"
    >
      <el-table-column
        type="selection"
        width="55"
        align="center"
      />
      <el-table-column
        label="Дата"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Прибор"
      >
        <template slot-scope="{row}">
          <span>{{ row.type_name[1] }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Показания"
        width="180"
        align="center"
      >
        <template slot-scope="{row}">
          <span>{{ row.value }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Разница"
        width="180"
        align="center"
      >
        <template slot-scope="{row}">
          <span>{{ row.delta }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Тариф"
        width="180"
        align="center"
      >
        <template slot-scope="{row}">
          <span>{{ row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Сумма"
        prop="summa"
        width="180"
        align="center"
      >
        <template slot-scope="{row}">
          <span>{{ row.summa | formatPrice }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label=""
        width="200"
        align="center"
      >
        <template slot-scope="{row}">
          <div>
            <el-button v-if="!row.invoice_id && row.summa > 0" type="primary" @click="addInvoice(row)">Выставить счет</el-button>
          </div>
          <span v-if="row.invoice_id">Счет № {{ row.invoice_id }}</span>
        </template>
      </el-table-column>
    </el-table>
    <!--    </div>-->
    <div v-if="selectRows.length > 0" class="filter-container mt2">
      <el-select v-model="action" placeholder="Действие">
        <el-option
          v-for="item in actionList"
          :key="item.key"
          :label="item.label"
          :value="item.key"
        />
      </el-select>
      <el-button type="primary" :disabled="!action" @click="actionSubmite">Выполнить</el-button>
    </div>
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
import { addInvoiceForReadings, addInvoiceForGroupReadings } from '@/api/admin/bookkeping/invoice'

export default {
  components: { AddReadingDialog },
  data() {
    return {
      selectRows: [],
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
      },
      action: 2,
      actionList: [
        {
          key: 2,
          label: 'Сгруппировать по месяцам и выставить счет'
        },
        {
          key: 1,
          label: 'Выставить отдельные счета'
        }
        // {
        //   key: 3,
        //   label: 'Выставить 1 счет на все'
        // },
      ]

    }
  },
  mounted() {
    this.id = this.$route.params && this.$route.params.id
    this.getList()
    this.getTypeList()
  },
  methods: {
    actionSubmite() {
      const text = this.actionList.find(item => {
        if (this.action === item.key) {
          return true
        }
      })
      this.$confirm(text.label + '?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        const data = {
          action: this.action,
          readings: this.selectRows.map(item => { return item.id })
        }
        // console.log(data)
        addInvoiceForGroupReadings(data)
          .then(response => {
            if (response.data.status) {
              this.$message('Данные успешно сохранены')
              this.getList()
              this.$emit('reload')
            } else if (response.data.data) {
              this.$message.error(response.data.data)
            }
          })
      }).catch(() => {

      })
    },
    handleSelectionChange(val) {
      // console.log(val)
      this.selectRows = val
    },
    reload() {
      this.$emit('reload')
      this.getList()
      this.getTypeList()
    },
    addInvoice(row) {
      this.$confirm('Выставить счет на сумму ' + row.summa + ' руб.?', 'Внимание!', {
        confirmButtonText: 'Выставить',
        cancelButtonText: 'Отмена',
        type: 'warning'
      }).then(() => {
        addInvoiceForReadings(row.id)
          .then(response => {
            if (response.data.status) {
              this.$message('Счет успешно добавлен')
              this.reload()
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      }).catch(() => {

      })
    },
    addReadings() {
      this.addReadingDialogShow = true
    },
    closeReadingsDialog() {
      this.$emit('reload')
      this.addReadingDialogShow = false
    },
    // getSummaries(param) {
    //   const { columns, data } = param
    //   const sums = []
    //   columns.forEach((column, index) => {
    //     if (index === 0) {
    //       sums[index] = 'Итого'
    //       return
    //     }
    //     if (column.property === 'summa') {
    //       const values = data.map(item => {
    //         return item.summa
    //       })
    //       if (!values.every(value => isNaN(value))) {
    //         sums[index] = values.reduce((prev, curr) => {
    //           const value = Number(curr)
    //           if (!isNaN(value)) {
    //             return prev + curr
    //           } else {
    //             return prev
    //           }
    //         }, 0)
    //       } else {
    //         sums[index] = 'N/A'
    //       }
    //     }
    //   })
    //   sums[5] = sums[5].toFixed(2)
    //   return sums
    // },
    getList() {
      fetchCommunalListForStead(this.id, this.listQuery)
        .then(response => {
          if (response.data.status) {
            // this.list = []
            // this.summa = 0
            this.list = response.data.data
            // response.data.data.forEach(item => {
            //   if (item.device_id === 1) {
            //     item.delta = item.value - this.old1
            //     item.summa = item.delta * item.price
            //     this.summa += item.summa
            //     this.list.push(item)
            //     this.old1 = +item.value
            //   }
            //   if (item.device_id === 2) {
            //     item.delta = item.value - this.old2
            //     item.summa = item.delta * item.price
            //     this.summa += item.summa
            //     this.list.push(item)
            //     this.old2 = +item.value
            //   }
            // })
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
  .scroll-block {
    max-height: 400px;
    overflow-y: scroll;
    scrollbar-width: thin;
    border: 1px solid;
    margin-bottom: 10px;
  }
</style>
