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
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Номер участка" align="center" width="160">
        <template slot-scope="{row}">
          <span>{{ row.stead_number }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Назначение" align="center">
        <template slot-scope="{row}">
          <span>{{ row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Сумма" align="center" width="160">
        <template slot-scope="{row}">
          <span>{{ row.price | formatPrice }} руб.</span>
        </template>
      </el-table-column>

      <el-table-column label="" align="center" width="300">
        <template slot-scope="{row}">
          <span>
            <el-button type="primary" size="small" style="width: 90px" @click="showMore(row)">Подробнее</el-button>
            <el-button v-if="row.payment_id" type="success" size="small" style="width: 90px" @click="showPayment(row)">Оплачен</el-button>
            <el-button v-else type="danger" size="small" plain @click="printPdf(row)">Квитанция</el-button>
          </span>
        </template>
      </el-table-column>
    </el-table>
    <InvoiceInfo v-if="showInvoiceInfo" :id="itemSelected.id" @close="closeInvoiceInfo" />
    <PaymentInfo v-if="showPaymentInfo" :id="itemSelected.payment_id" @close="closePaymentInfo" />
  </div>
</template>

<script>
import InvoiceInfo from '@/components/BillingInvoiceInfo'
import PaymentInfo from '@/components/BillingPaymetnInfo'
import { getReceiptForInvoice } from '@/api/admin/receipt'
import { saveAs } from 'file-saver'

export default {
  components: {
    InvoiceInfo,
    PaymentInfo
  },
  props: {
    list: {
      type: Array,
      default: () => { [] }
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
          saveAs(new Blob([response.data], {
            type: response.data.type
          }), 'Квитанция.pdf')
          this.$message.success('Фаил успешно скачен.')
        })
    }
  }
}
</script>

<style scoped>

</style>
