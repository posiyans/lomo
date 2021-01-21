<template>
  <div class="createPost-container">
    <div class="pt1 createPost-main-container" style="padding-top: 0; padding-bottom: 0">
      <el-button v-loading="loading" type="primary" @click="close">
        Все платежи
      </el-button>
    </div>
    <div class="createPost-main-container billing-bank-reestr-table">
      {{ typeName }}
      <el-table
        :data="list"
        border
        style="width: 100%"
        :row-class-name="tableRowClassName"
      >
        <el-table-column
          label="Дата"
          align="center"
          width="150px"
        >
          <template slot-scope="{ row }">
            <span>{{ row.payment_date | moment("DD-MM-YYYY HH:mm") }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="Сумма"
          align="center"
          width="80px"
        >
          <template slot-scope="{ row }">
            <span>{{ row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="Участок"
          align="center"
          width="150px"
        >
          <template slot-scope="{ row }">
            <span v-if="row.raw_data[2] === row.stead.number">{{ row.stead.number }}</span>
            <span v-else>
              {{ row.raw_data[2] }} -> {{ row.stead.number }}
            </span>
          </template>
        </el-table-column>
        <el-table-column
          label="Назначение"
          align="center"
          width="150px"
        >
          <template slot-scope="{ row }">
            <span>{{ row.raw_data[3] }}</span>
          </template>
        </el-table-column>
        <el-table-column
          label="ФИО"
          align="center"
          width="150px"
        >
          <template slot-scope="{ row }">
            <span>{{ row.raw_data[4] }}</span>
          </template>
        </el-table-column>
        <el-table-column
          v-for="i in sort"
          :key="i"
          :label="labelForm[i]"
          align="center"
        >
          <template slot-scope="{row}">
            <span>{{ row.raw_data[i] }}</span>
            <span v-if="i == 5">
              <span v-if="row.stead">
                --> <el-tag :type="row | steadFilter" @click="editStead(row)">{{ row.stead.number }}</el-tag>
              </span>
              <span v-else>
                --> <el-tag :type="row | steadFilter" @click="editStead(row)">---</el-tag>
              </span>
            </span>
          </template>
        </el-table-column>
        <el-table-column
          prop="date"
          label="Оплата"
          align="center"
        >
          <template slot-scope="{row}">
            <div v-if="row.dubl">
              <el-tag type="danger" effect="dark">
                Повтор
              </el-tag>
            </div>
            <div v-else>

              <el-dropdown @click.native="dropClick(row)" @command="setType">
                <span class="el-dropdown-link">
                  <span v-if="typeName[row.type]">
                    {{ typeName[row.type].name }}
                  </span>
                  <span v-else class="dark-red">-------!</span>
                  <i class="el-icon-arrow-down el-icon--right" />
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item
                    v-for="item in typeList"
                    :key="item.id"
                    :command="item.id"
                  >
                    {{ item.name }}
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <!--                <div v-if="row.type == 1">-->
              <!--                  <el-tag v-if="row.meterReading1">1-{{ row.meterReading1 }}</el-tag>-->
              <!--                  <el-tag v-if="row.meterReading2">2-{{ row.meterReading2 }}</el-tag>-->
              <!--                </div>-->
            </div>
          </template>
        </el-table-column>
        <el-table-column
          label=""
          align="center"
          width="180px"
        >
          <template slot-scope="{ row }">
            <span v-if="!row.dubl && row.error">
              <el-button type="success" @click="paymentOk(row)">Подтвердить</el-button>
            </span>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div v-if="dialogSteadFormVisible">
      <el-dialog
        title="Укажите участок"
        :visible.sync="dialogSteadFormVisible"
      >
        <UserSteadFind :read_only="false" :stead_id="editRow.stead.id" @selectStead="setStead" />
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogSteadFormVisible = false">Отмена</el-button>
          <el-button type="primary" @click="confirmStead">Ок</el-button>
        </span>
      </el-dialog>
    </div>
    <div v-if="dialogMeterReadingFormVisible">
      <el-dialog title="Введите показания" :visible.sync="dialogMeterReadingFormVisible">
        <div class="mb2">{{ editRow.val7 }}</div>
        <el-form label-position="top">
          <el-form-item label="Показания день">
            <el-input
              v-model="editRow.meterReading1"
              placeholder="Показания день"
              prefix-icon="el-icon-search"
            />
          </el-form-item>
          <el-form-item label="Показания ночь">
            <el-input
              v-model="editRow.meterReading2"
              placeholder="Показания ночь"
              prefix-icon="el-icon-search"
            />
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogMeterReadingFormVisible = false">Cancel</el-button>
          <el-button type="primary" @click="dialogMeterReadingFormVisible = false">Confirm</el-button>
        </span>
      </el-dialog>
    </div>
  </div>
</template>

<script>

import UserSteadFind from '@/components/UserSteadFind'
import { updatePaymentInfo } from '@/api/admin/bookkeping/payment'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'

const labelForm = {
  0: 'Дата',
  2: 'Участок',
  3: 'ФИО',
  4: 'Назначение',
  1: 'Сумма'
}

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
    },
    steadFilter(val) {
      if (val.stead && val.raw_data[2] === val.stead.number) {
        return ''
      }
      return 'danger'
    }
  },
  // name: 'ArticleDetail',
  components: { UserSteadFind },
  props: {
    list: {
      type: Array,
      default: () => ([])
    },
    isEdit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      editType: '',
      typeList: [],
      typeName: {},
      sort: [],
      labelForm,
      editRow: {},
      dialogSteadFormVisible: false,
      dialogMeterReadingFormVisible: false,
      tempStead: {},
      loading: false,
      userListOptions: []
    }
  },
  computed: {
  },
  created() {
    this.getTypeList()
  },
  methods: {
    paymentOk(row) {
      row.error = false
      this.savePayment(row)
    },
    dropClick(row) {
      this.editType = row
    },
    setType(val) {
      this.editType.type = val
      this.savePayment(this.editType)
    },
    savePayment(data) {
      updatePaymentInfo(data.id, data)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            // todo вернуть данные наверх!!!!
            // this. = response.data.data
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
            this.typeList = response.data.data
            response.data.data.forEach(item => {
              console.log('item')
              console.log(item)

              this.typeName = Object.assign({}, this.typeName, { [item.id]: item })
            })
            console.log(this.typeName)
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    close() {
      this.$emit('close')
    },
    tableRowClassName({ row, rowIndex }) {
      // console.log(row)
      if (row.dubl) {
        return 'warning-row'
      }
      if (row.error) {
        return 'warning-row'
      }
      return ''
    },
    changeType(row, type) {
      if (row.type !== type) {
        row.type = type
        this.saveData(row.id, { type: row.type })
      }
      if (type === 1) {
        this.editRow = row
        this.dialogMeterReadingFormVisible = true
      }
    },
    saveData(id, data) {
      updatePaymentInfo(id, data)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    confirmStead() {
      this.dialogSteadFormVisible = false
      if (this.tempStead && this.tempStead.id) {
        this.editRow.stead.id = this.tempStead.id
        this.editRow.stead.number = this.tempStead.number
        this.saveData(this.editRow.id, { stead_id: this.tempStead.id })
        if (this.editRow.type) {
          this.editRow.error = false
        }
      }
    },
    setStead(stead) {
      this.tempStead = stead
      // this.editRow.stead.id = stead.id
      // this.editRow.stead.number = stead.number
    },
    editStead(row) {
      if (!row.dubl) {
        this.tempStead = false
        this.editRow = row
        this.dialogSteadFormVisible = true
      }
    }

  }
}
</script>

<style scoped>

</style>
