<template>
  <div style="margin-top: 20px">
    <el-table
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column label="id" align="center" width="60">
        <template #default="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Номер участка" align="center" width="160">
        <template #default="{row}">
          <span>{{ row.stead_number }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Назначение" align="center">
        <template #default="{row}">
          <span>{{ row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Сумма" align="center" width="160">
        <template #default="{row}">
          <span>{{ row.price }} руб.</span>
        </template>
      </el-table-column>

      <el-table-column label="" align="center" width="300">
        <template #default="{row}">
          <span>
            <el-button type="primary" style="width: 90px" @click="showMore(row)">Подробнее</el-button>
            <el-button v-if="row.payment_id" type="success" style="width: 90px" @click="showPayment(row)">Оплачен</el-button>
            <el-button v-else type="danger" plain @click="printPdf(row)">Квитанция</el-button>
          </span>
        </template>
      </el-table-column>
    </el-table>
    <InvoiceInfo v-if="showInvoiceInfo" :id="itemSelected.id" @close="closeInvoiceInfo" />
    <PaymentInfo v-if="showPaymentInfo" :id="itemSelected.payment_id" @close="closePaymentInfo" />
  </div>
</template>

<script>
import InvoiceInfo from 'src/Modules/Bookkeeping/components/Invoice/BillingInvoiceInfo/index.vue'
import PaymentInfo from 'src/Modules/Bookkeeping/components/Playment/BillingPaymetnInfo/index.vue'
import { getReceiptForInvoice } from 'src/Modules/Receipt/api/receiptAdminApi.js'
import { exportFile } from 'quasar'

export default {
  components: {
    InvoiceInfo,
    PaymentInfo
  },
  props: {
    list: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      total: 0,
      showPaymentInfo: false,
      showInvoiceInfo: false,
      itemSelected: ''

    }
  },
  methods: {
    showPayment(row) {
      this.itemSelected = row
      this.showPaymentInfo = true
    },
    closePaymentInfo() {
      this.showPaymentInfo = false
      // this.$emit('reload')
    },
    closeInvoiceInfo() {
      this.showInvoiceInfo = false
    },
    showMore(row) {
      this.itemSelected = row
      this.showInvoiceInfo = true
    },
    printPdf(row) {
      const data = {
        invoices: [row.id]
      }
      getReceiptForInvoice(data)
        .then(response => {
          exportFile('Квитанция.pdf', new Blob([response.data], {
            type: response.data.type
          }))
          this.$message.success('Файл успешно скачан.')
        })
    }
  }
}
</script>

<style scoped>

</style>
