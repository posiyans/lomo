<template>
  <div>
    <el-dialog
      title="Счет"
      v-model="dialogVisible"
      top="15px"
      :width="mobile ? '100%' : '800px;'"
      @close="close"
    >
      <table border="1" class="do-not-carry black">
        <tr class="bg-black-05">
          <th>Поле</th>
          <th>Значение</th>
          <th />
        </tr>
        <tr>
          <td>id</td>
          <td>{{ invoice.id }}</td>
          <td />
        </tr>
        <tr>
          <td>Дата</td>
          <td>
            <ShowTime :time="invoice.created_at" format="DD-MM-YYYY" />
          </td>
          <td />
        </tr>
        <tr class="black">
          <td>Участок</td>
          <td>
            <span>
              {{ invoice.stead_number }}
            </span>
          </td>
          <td />
        </tr>
        <tr>
          <td>Сумма</td>
          <td :class="priceLineClass">
            {{ invoice.price }} руб.
          </td>
          <td :class="priceLineClass" />
        </tr>
        <tr :class="{ 'dark-green': invoice.paid, 'dark-red': !invoice.paid }">
          <td>Статус</td>
          <td>
            <div v-if="!editStatusShow">
              <span v-if="invoice.paid" class="do-not-carry">
                Оплачен
              </span>
              <span v-if="!invoice.paid" class="do-not-carry">
                Неоплачен
              </span>
            </div>
            <el-button-group v-if="editStatusShow">
              <el-button type="danger" :plain="invoice.paid" size="mini" @click="setStatus(false)">Неоплачен</el-button>
              <el-button type="success" :plain="!invoice.paid" size="mini" @click="setStatus(true)">Оплачен</el-button>
            </el-button-group>
          </td>
          <td>
            <el-button type="primary" size="mini" :plain="!editStatusShow" icon="el-icon-edit" @click="editStatus" />
          </td>
        </tr>
        <tr v-if="editStatusShow">
          <td colspan="3">
            Добавить платеж к счету
            <el-table
              border
              :data="payments"
              empty-text="Подходящие платежи не найдены"
              style="width: 100%"
            >
              <el-table-column
                label="Дата"
                width="100"
                align="center"
              >
                <template #default="{row}">
                  <span>
                    <ShowTime :time="row.data.payment_date" format="DD-MM-YYYY" />
                  </span>
                </template>
              </el-table-column>
              <el-table-column
                label="Сумма"
                width="100"
                align="center"
              >
                <template #default="{row}">
                  <span>{{ row.data.price }}</span>
                </template>
              </el-table-column>
              <el-table-column
                label="Назаначение"
              >
                <template #default="{row}">
                  <span>{{ row.data.raw_data[4] }}</span>
                </template>
              </el-table-column>
              <el-table-column
                label=""
                width="50"
                align="center"
              >
                <template #default="{row}">
                  <div @click="addPayment(row)">
                    <i class="el-icon-circle-plus dark-green f4" />
                  </div>
                </template>
              </el-table-column>
            </el-table>
          </td>
        </tr>
        <tr>
          <td>Оплата</td>
          <td>
            <ol>
              <li v-for="i in invoice.payments" :key="i.id" class="do-not-carry tl gray">
                <ShowTime :time="i.payment_date" format="DD-MM-YYYY" />
                на сумму {{ i.price }} руб
                <span v-if="editStatusShow" @click="deletePayment(i)">
                  <i class="el-icon-close dark-red f4 pointer" />
                </span>
              </li>
            </ol>
          </td>
          <td :class="priceLineClass">
            <em>
              Итого: {{ sumPayment }} руб
            </em>
          </td>
        </tr>
        <tr>
          <td>Назначение</td>
          <td>
            {{ invoice.title }}
          </td>
          <td />
        </tr>
        <tr class="black">
          <td>Тип платежа</td>
          <td>
            <span>
              {{ invoice.type_name }}
            </span>
          </td>
          <td />
        </tr>
        <tr>
          <td>Примечание</td>
          <td>
            <div v-for="item in descriptionList" :key="item" class="f7">{{ item }}</div>
          </td>
          <td>
            <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editDescription" />
          </td>
        </tr>
      </table>
      <span class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">Закрыть</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { addPaymentForInvoice, changeStatusForInvoice, deletePaymentForInvoice, fetchInvoiceInfo, updateInvoice } from 'src/Modules/Bookkeeping/api/invoiceAdminApi.js'
import { fetchReceiptTypeList } from 'src/Modules/Receipt/api/receiptAdminApi.js'
import { fetchBillingBalansSteadInfo } from 'src/Modules/Bookkeeping/api/billingAminApi.js'
import ShowTime from 'components/ShowTime/index.vue'

export default {
  components: {
    ShowTime
  },
  props: {
    id: {
      type: [Number, String],
      default: ''
    }
  },
  data() {
    return {
      key: 1,
      editStatusShow: false,
      invoice: {},
      findSteadShow: false,
      dialogVisible: true,
      meteringShow: false,
      showRaw: false,
      tempStead: null,
      receipType: [],
      payments: []
    }
  },
  computed: {
    mobile() {
      return this.$q.platform.is.mobile
    },
    descriptionList() {
      return this.invoice.description.split('@')
    },
    priceLineClass() {
      if (this.sumPayment === this.invoice.price) {
        return 'green'
      }
      return 'red'
    },
    sumPayment() {
      let paySum = 0
      this.invoice.payments.forEach(i => {
        paySum += i.price
      })
      return paySum
    }
  },
  mounted() {
    this.getInvoiceData()
    this.getReceipt()
  },
  methods: {
    setStatus(val) {
      let text = 'Сменить стутус на НЕОПЛАЧЕННЫЙ?'
      if (val) {
        text = 'Сменить стутус на ОПЛАЧЕННЫЙ?'
      }
      this.$confirm(text, 'Смена статуса', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        const data = {
          invoice_id: this.invoice.id,
          status: val
        }
        changeStatusForInvoice(data)
          .then(response => {
            if (response.data.status) {
              this.getInvoiceData()
              this.getPayment()
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      }).catch(() => {
      })
    },
    addPayment(row) {
      // this.invoice.payments.push(row.data)
      const data = {
        payment_id: row.data.id,
        invoice_id: this.invoice.id
      }
      addPaymentForInvoice(data)
        .then(response => {
          if (response.data.status) {
            this.getInvoiceData()
            this.getPayment()
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    deletePayment(row) {
      const data = {
        payment_id: row.id,
        invoice_id: this.invoice.id
      }
      deletePaymentForInvoice(data)
        .then(response => {
          if (response.data.status) {
            this.getInvoiceData()
            this.getPayment()
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
      // let k = 0
      // this.invoice.payments.forEach(i => {
      //   if (i.id === row.id) {
      //     this.invoice.payments.splice(k, 1)
      //   }
      //
      //   k++
      // })
    },
    editStatus() {
      this.editStatusShow = !this.editStatusShow
      this.getPayment()
    },
    getPayment() {
      const data = {
        stead_id: this.invoice.stead_id,
        receiptType: this.invoice.type,
        type: 'payment_without_invoice'
        // type: 'payment'
      }
      fetchBillingBalansSteadInfo(data)
        .then(response => {
          if (response.data.status) {
            // this.$message('Данные успешно сохранены')
            this.payments = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    getInvoiceData() {
      fetchInvoiceInfo(this.id)
        .then(response => {
          if (response.data.status) {
            this.invoice = response.data.data
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
    getReceipt() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receipType = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    editDescription() {
      this.$prompt('Добавить', 'Примечание', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.invoice.description
      }).then(({ value }) => {
        this.invoice.description = value
        this.saveData()
      }).catch(() => {
      })
    },
    saveData() {
      updateInvoice(this.invoice.id, this.invoice)
        .then(response => {
          if (response.data.status) {
            this.getInvoiceData()
            // this.close()
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
ol {
  margin: 0;
}

table {
  border-collapse: collapse;
  margin: 25px;
}

td, th {
  border: 1px solid #606266;
  padding: 5px 10px;
  text-align: center;
}

.el-dropdown-link {
  cursor: pointer;
  color: #409EFF;
}

.el-icon-arrow-down {
  font-size: 12px;
}
</style>
