<template>
  <div>
    <q-table
      flat
      bordered
      :rows="list"
      :columns="columns"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
      wrap-cells
      separator="cell"
      row-key="id"
    >
      <template v-slot:body="props">
        <q-tr :props="props" :class="{ 'bg-red-1': props.row.type.uid === 'invoice',  'bg-teal-1': props.row.type.uid === 'payment' }" :style="props.row.is_paid ?  'opacity: .5': ''">
          <q-td auto-width>
            <div class="text-center">
              {{ props.row.id }}
            </div>
          </q-td>
          <q-td auto-width>
            <ShowTime :time="props.row.date" format="DD-MM-YYYY" class="text-center" />
            <div class="text-center">
              {{ props.row.type.label }}
            </div>
          </q-td>
          <q-td auto-width>
            <div class="text-no-wrap">
              <ShowPrice :price="props.row.price" class="text-center" />
              <div v-if="props.row.is_paid" class="text-teal text-small-85 text-center">
                Оплачен
              </div>
            </div>
          </q-td>
          <q-td>
            <div class="row items-center">
              <div>
                {{ props.row?.title }}
              </div>
              <q-space />
              <div v-if="props.row.type.uid === 'payment' && props.row.rate.depends === 2">
                <ShowReadingListInPayment :payment="props.row" />
              </div>
            </div>
          </q-td>
          <q-td>
            {{ props.row.rate?.name }}
          </q-td>
          <q-td>
            <div class="row justify-center">
              <PaymentInfo v-if="props.row.type.uid === 'payment'" :payment="props.row" :edit="edit" @reload="reload" @deletePayment="reload" />
              <InvoiceInfo v-if="props.row.type.uid === 'invoice'" :invoice="props.row" :edit="edit" @reload="reload" @deletePayment="reload" />
            </div>
          </q-td>
        </q-tr>
      </template>
    </q-table>

  </div>
</template>

<script>
import { defineComponent } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import PaymentInfo from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/Dialog.vue'
import InvoiceInfo from 'src/Modules/Bookkeeping/components/Invoice/InvoiceInfo/Dialog.vue'
import ShowReadingListInPayment from 'src/Modules/Bookkeeping/components/Payment/ShowReadingListInPayment/index.vue'

export default defineComponent({
  components: {
    ShowReadingListInPayment,
    PaymentInfo,
    InvoiceInfo,
    ShowTime,
    ShowPrice
  },
  props: {
    list: {
      type: Array,
      default: () => []
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const columns = [
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
        name: 'actions',
        align: 'center',
        label: ''
      }
    ]
    const reload = () => {
      emit('reload')
    }
    return {
      columns,
      reload
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
