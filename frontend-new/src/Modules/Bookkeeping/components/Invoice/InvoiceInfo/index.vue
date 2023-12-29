<template>
  <div>
    <table class="do-not-carry black">
      <tr class="bg-black-05">
        <th>Поле</th>
        <th>Значение</th>
      </tr>
      <tr>
        <td>id</td>
        <td>{{ invoice.id }}</td>
      </tr>
      <tr>
        <td>Дата</td>
        <td>
          <ShowTime :time="invoice.created_at" format="DD-MM-YYYY" />
        </td>
      </tr>
      <tr class="black">
        <td>Участок</td>
        <td>
          <div>
            № {{ invoice.stead.number }} <span class="text-grey text-small-80">({{ invoice.stead.size }} м<sup>2</sup>)</span>
          </div>
        </td>
      </tr>
      <tr>
        <td>Сумма</td>
        <td :class="priceLineClass">
          {{ invoice.price }} руб.
        </td>
      </tr>
      <tr :class="{ 'text-green': invoice.is_paid, 'text-red': !invoice.is_paid }">
        <td>Статус</td>
        <td class="relative-position">
          <div v-if="!editStatusShow">
            {{ invoice.is_paid ? 'Оплачен' : 'Неоплачен' }}
          </div>
          <el-button-group v-if="editStatusShow">
            <el-button type="danger" :plain="invoice.paid" size="mini" @click="setStatus(false)">Неоплачен</el-button>
            <el-button type="success" :plain="!invoice.paid" size="mini" @click="setStatus(true)">Оплачен</el-button>
          </el-button-group>
          <div class="absolute-top-right">
            <q-btn color="primary" flat size="xs" icon="edit" @click="editStatus" />
          </div>
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
        <td class="text-left">
          <div class="text-left">
            <ol>
              <li v-for="i in invoice.payments" :key="i.id" class="text-no-wrap text-grey-7">
                <ShowTime :time="i.payment_date" block="span" format="DD-MM-YYYY" />
                на сумму {{ i.price }} руб.
                <span v-if="editStatusShow" @click="deletePayment(i)">
                  <q-icon name="delete" />
                </span>
              </li>
            </ol>
          </div>
          <div :class="priceLineClass">
            <em>
              Итого: {{ sumPayment }} руб.
            </em>
          </div>
        </td>
      </tr>
      <tr>
        <td>Назначение</td>
        <td>
          {{ invoice.title }}
        </td>

      </tr>
      <tr class="black">
        <td>Тип платежа</td>
        <td>
            <span>
              {{ invoice.type_name }}
            </span>
        </td>

      </tr>
      <tr>
        <td>Примечание</td>
        <td class="relative-position">
          <div v-for="item in descriptionList" :key="item" class="f7">{{ item }}</div>
          <div class="absolute-top-right">
            <q-btn color="primary" flat size="xs" icon="edit" @click="editDescription" />
          </div>
        </td>
      </tr>
    </table>
    <div v-if="edit" class="q-pa-sm">
      <DeleteInvoiceBtn :invoice-id="invoice.id" @success="reload" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { addPaymentForInvoice, changeStatusForInvoice, deletePaymentForInvoice, fetchInvoiceInfo, updateInvoice } from 'src/Modules/Bookkeeping/api/invoiceAdminApi.js'
import { fetchReceiptTypeList } from 'src/Modules/Receipt/api/receiptAdminApi.js'
import { fetchBillingBalansSteadInfo } from 'src/Modules/Bookkeeping/api/billingAminApi.js'
import ShowTime from 'components/ShowTime/index.vue'
import DeleteInvoiceBtn from 'src/Modules/Bookkeeping/components/Invoice/DeleteInvoiceBtn/index.vue'

export default {
  components: {
    ShowTime,
    DeleteInvoiceBtn
  },
  props: {
    invoice: {
      type: Object,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      key: 1,
      editStatusShow: false,
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
    descriptionList() {
      return this.invoice.description.description.split('@')
    },
    priceLineClass() {
      if (this.sumPayment === +this.invoice.price) {
        return 'green'
      }
      return 'red'
    },
    sumPayment() {
      let paySum = 0
      this.invoice?.payments.forEach(i => {
        paySum += i.price
      })
      return paySum
    }
  },
  mounted() {
    // this.getInvoiceData()
    this.getReceipt()
  },
  methods: {
    reload() {
      this.$emit('success')
    },
    setStatus(val) {
      let text = 'Сменить стутус на НЕОПЛАЧЕННЫЙ?'
      if (val) {
        text = 'Сменить стутус на ОПЛАЧЕННЫЙ?'
      }
      this.$q.dialog({
        title: 'Подтвердите',
        message: text,
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Нет',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Да',
          color: 'primary'
        },
        persistent: true
      }).onOk(() => {
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
        inputValue: this.invoice.description.description
      }).then(({ value }) => {
        this.invoice.description.description = value
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
}

td, th {
  border: 1px solid #606266;
  padding: 5px 10px;
}
</style>
