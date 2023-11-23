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
        width="120"
        align="center"
      >
        <template #default="{row}">
          <ShowTime :time="row.created_at" format="DD-MM-YYYY" />
        </template>
      </el-table-column>
      <el-table-column
        label="Прибор"
      >
        <template #default="{row}">
          <span>{{ row.type_name[1] }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Показания"
        width="180"
        align="center"
      >
        <template #default="{row}">
          <span>{{ row.value }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Разница"
        width="180"
        align="center"
      >
        <template #default="{row}">
          <span>{{ row.delta }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Тариф"
        width="180"
        align="center"
      >
        <template #default="{row}">
          <span>{{ row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Сумма"
        prop="summa"
        width="180"
        align="center"
      >
        <template #default="{row}">
          <span>{{ row.summa }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label=""
        width="220"
        align="center"
      >
        <template #default="{row}">
          <div>
            <el-button-group>
              <el-button v-if="!row.invoice_id && row.summa > 0" icon="el-icon-circle-plus" type="primary" @click="addInvoice(row)" />
              <el-button v-if="!row.invoice_id && row.summa > 0" icon="el-icon-delete" type="danger" @click="deleteReading(row)" />
            </el-button-group>
          </div>
          <span v-if="row.invoice_id">Счет № {{ row.invoice_id }}</span>
        </template>
      </el-table-column>
    </el-table>
    <LoadMore v-if="id" :id="id" :key="key" :list-query="listQuery" :func="func" @setList="setList" />

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
import { deleteInstrumentReadings, fetchCommunalListForStead } from 'src/Modules/Bookkeeping/api/communalAdminApi.js'
import { fetchReceiptTypeList, getReceiptTypeInfo } from 'src/Modules/Receipt/api/receiptAdminApi.js'
import AddReadingDialog from './components/AddReadingsDialog'
import { addInvoiceForGroupReadings, addInvoiceForReadings } from 'src/Modules/Bookkeeping/api/invoiceAdminApi.js'
import LoadMore from 'src/components/LoadMore'
import ShowTime from 'components/ShowTime/index.vue'

export default {
  components: { AddReadingDialog, LoadMore, ShowTime },
  data() {
    return {
      key: 1,
      func: fetchCommunalListForStead,
      selectRows: [],
      addReadingDialogShow: false,
      list: [],
      id: '333',
      old1: 39100,
      old2: 16600,
      summa: 0,
      itemsTypeList: [],
      primaryType: '',
      typeList: [],
      listQuery: {
        limit: 20,
        page: 1,
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
      ]

    }
  },
  created() {
    this.id = this.$route.params && this.$route.params.id
  },
  mounted() {
    // this.getList()
    this.getTypeList()
  },
  methods: {
    actionSubmite() {
      // eslint-disable-next-line
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
          readings: this.selectRows.map(item => {
            return item.id
          })
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
    deleteReading(row) {
      this.$confirm('Удалить показания?', 'Внимание!', {
        confirmButtonText: 'Удалить',
        cancelButtonText: 'Отмена',
        type: 'danger'
      }).then(() => {
        deleteInstrumentReadings(row.id)
          .then(response => {
            if (response.data.status) {
              this.$message('Данные успешно удалены')
              this.getList()
            } else if (response.data.error) {
              this.$message.error(response.data.error)
            }
          })
      }).catch(() => {

      })
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
    setList(val) {
      console.log(val)
      this.list = val
    },
    getList() {
      this.listQuery.page = 1
      this.key++
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
