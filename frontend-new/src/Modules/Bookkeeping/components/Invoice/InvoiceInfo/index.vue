<template>
  <div>
    <table class="do-not-carry black">
      <tr class="bg-black-05" :class="{ 'bg-teal-1': invoice.is_paid, 'text-red': !invoice.is_paid }">
        <th>Поле</th>
        <th>
          <div class="row items-center">
            <div class="col-grow">
              <div>
                Значение
              </div>
            </div>
            <div v-if="edit">
              <q-fab
                flat
                text-color="black"
                icon="more_vert"
                direction="left"
                padding="xs"
              >
                <div class="q-pa-sm">
                  <DeleteInvoiceBtn :invoice-id="invoice.id" @success="reload" />
                </div>
              </q-fab>

            </div>
          </div>
        </th>
      </tr>
      <tr>
        <td>id {{ invoice.id }}</td>
        <td>
          <div class="text-primary text-weight-bold">
            {{ invoice.rate.name }}
          </div>
        </td>
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
          <ShowPrice :price="invoice.price" />
        </td>
      </tr>
      <tr>
        <td>Оплата</td>
        <td class="text-left">
          <div class="text-left">
            <ol>
              <li v-for="i in invoice.payments" :key="i.id">
                <div class="row q-col-gutter-xs text-no-wrap text-grey-7">
                  <ShowTime :time="i.payment_date" block="span" format="DD-MM-YYYY" />
                  <ShowPrice :price="i.price" before-text="на сумму" />
                  <q-space />
                  <div v-if="edit" @click="deletePayment(i)">
                    <q-btn icon="delete" color="negative" flat dense round size="xs" />
                  </div>
                </div>
              </li>
            </ol>
          </div>
          <ShowPrice :price="sumPayment" before-text="Итого:" :class="priceLineClass" class="text-weight-bold" />
        </td>
      </tr>
      <TrStatus :invoice="invoice" :edit="edit" />
      <tr>
        <td>Назначение</td>
        <td>
          {{ invoice.title }}
        </td>

      </tr>
      <tr>
        <td>Подробнее</td>
        <td class="relative-position">
          <EditInvoiceDescription
            v-if="editDescriptionShow"
            :invoice="invoice"
            @reload="editDescriptionShow = false"
          />
          <div v-else>
            <div v-for="item in descriptionList" :key="item" class="text-small-70">
              {{ item }}
            </div>
          </div>
          <div class="absolute-top-right">
            <q-btn color="primary" flat size="xs" icon="edit" @click="editDescriptionShow = !editDescriptionShow" />
          </div>
        </td>
      </tr>
      <tr>
        <td>Примечание</td>
        <td class="relative-position">
          <EditInvoiceDescription
            v-if="editCommentShow"
            :invoice="invoice"
            field-name="comment"
            @reload="editCommentShow = false"
          />
          <div v-else>
            <div class="text-small-70">
              {{ invoice.options?.comment }}
            </div>
          </div>
          <div class="absolute-top-right">
            <q-btn color="primary" flat size="xs" icon="edit" @click="editCommentShow = !editCommentShow" />
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import DeleteInvoiceBtn from 'src/Modules/Bookkeeping/components/Invoice/DeleteInvoiceBtn/index.vue'
import InvoiceChangeStatus from 'src/Modules/Bookkeeping/components/Invoice/InvoiceChangeStatus/index.vue'
import EditInvoiceDescription from './components/EditInvoiceDescription/index.vue'
import PaymentList from 'src/Modules/Bookkeeping/components/Payment/PaymentList/index.vue'
import TrStatus from './components/TrStatus/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import { useQuasar } from 'quasar'
import { deletePaymentFromInvoice } from 'src/Modules/Bookkeeping/api/paymentApi'

export default defineComponent({
  components: {
    ShowTime,
    DeleteInvoiceBtn,
    InvoiceChangeStatus,
    EditInvoiceDescription,
    PaymentList,
    ShowPrice,
    TrStatus
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
    const data = ref(false)
    const $q = useQuasar()
    const editStatusShow = ref(false)
    const editDescriptionShow = ref(false)
    const editCommentShow = ref(false)
    const sumPayment = computed(() => {
      let paySum = 0
      props.invoice?.payments.forEach(i => {
        paySum += +i.price
      })
      return paySum
    })
    const priceLineClass = computed(() => {
      if (Math.floor(sumPayment.value) === Math.floor(props.invoice.price)) {
        return 'text-teal'
      }
      return 'text-red'
    })
    const descriptionList = computed(() => {
      if (props.invoice.options?.description) {
        return props.invoice.options?.description.split('@') || []
      }
      return []
    })
    const deletePayment = (val) => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Открепить платеж от счета?',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'primary'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Открепить',
          color: 'negative'
        },
        persistent: true
      }).onOk(() => {
        deletePaymentFromInvoice(val.id)
          .then(() => {
            props.invoice.is_paid = false
            props.invoice.payments.splice(props.invoice.payments.findIndex(item => item.id === val.id), 1)
          })
      })
    }
    const reload = () => {
      emit('success')
    }
    return {
      data,
      sumPayment,
      priceLineClass,
      editStatusShow,
      editCommentShow,
      editDescriptionShow,
      descriptionList,
      deletePayment,
      reload
    }
  }
})
</script>

<style scoped lang='scss'>
ol {
  margin: 0;
  padding-left: 12px;
}

table {
  border-collapse: collapse;
}

td, th {
  border: 1px solid #606266;
  padding: 5px 10px;
}
</style>
