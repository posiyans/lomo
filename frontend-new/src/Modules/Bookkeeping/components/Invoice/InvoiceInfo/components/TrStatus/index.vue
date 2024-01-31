<template>
  <tr :class="{ 'text-teal': invoice.is_paid, 'text-red': !invoice.is_paid }">
    <td>Статус</td>
    <td class="relative-position">
      <div v-if="edit" class="absolute-top-right">
        <q-btn color="primary" flat size="xs" icon="edit" @click="editStatusShow = !editStatusShow" />
      </div>
      <div v-if="!editStatusShow">
        {{ invoice.is_paid ? 'Оплачен' : 'Неоплачен' }}
      </div>
      <InvoiceChangeStatus v-if="editStatusShow" :invoice="invoice" class="q-pr-lg" @success="editStatusShow = false" />
    </td>
  </tr>
  <tr v-if="editStatusShow">
    <td colspan="2" style="padding: 0;">
      <div class="text-primary text-center ">
        Добавить платеж к счету
      </div>
      <PaymentList
        dense
        :key="key"
        :stead-id="invoice.stead_id"
        :rate-group-id="invoice.rate_group_id"
        :is-invoice="1"
        :columns="paymentColumns"
        style="min-height: 60px;"
        @selected="selectPayment"
      />
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import PaymentList from 'src/Modules/Bookkeeping/components/Payment/PaymentList/index.vue'
import InvoiceChangeStatus from 'src/Modules/Bookkeeping/components/Invoice/InvoiceChangeStatus/index.vue'
import { addPaymentInInvoice } from 'src/Modules/Bookkeeping/api/invoiceAdminApi'

export default defineComponent({
  components: {
    PaymentList,
    InvoiceChangeStatus
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
  setup(props, { emit }) {
    const key = ref(1)
    const editStatusShow = ref(false)
    const paymentColumns = [
      {
        name: 'id',
        align: 'center',
        label: '№'
      },
      {
        name: 'date',
        align: 'center',
        label: 'Дата'
      },
      {
        name: 'price',
        align: 'center',
        label: 'Сумма, руб'
      },

      {
        name: 'title',
        align: 'left',
        label: 'Назначение'
      },
      {
        name: 'group',
        align: 'center',
        label: 'Группа'
      },
      {
        name: 'select',
        align: 'center',
        label: ''
      }
    ]
    const selectPayment = (payment) => {
      const data = {
        payment_id: payment.id
      }
      addPaymentInInvoice(props.invoice.id, data)
        .then(() => {
          key.value++
          props.invoice.payments.push(payment)
          emit('success')
        })
      console.log(data)
    }
    const reload = () => {
      emit('success')
    }
    return {
      key,
      paymentColumns,
      editStatusShow,
      selectPayment,
      reload
    }
  }
})
</script>

<style scoped lang='scss'>
td {
  border: 1px solid #606266;
  padding: 5px 10px;
}
</style>
