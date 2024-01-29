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
      <template v-slot:body-cell-id="props">
        <q-td :props="props" auto-width>
          {{ props.row.id }}
        </q-td>
      </template>
      <template v-slot:body-cell-stead="props">
        <q-td :props="props" auto-width>
          {{ props.row.stead.number }}
        </q-td>
      </template>
      <template v-slot:body-cell-title="props">
        <q-td :props="props">
          {{ props.row.title }}
        </q-td>
      </template>
      <template v-slot:body-cell-price="props">
        <q-td :props="props">
          <ShowPrice :price="props.row.price" />
          <div v-if="props.row.is_paid" class="text-teal text-small-85">
            Оплачен
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-rate="props">
        <q-td :props="props">
          <div v-if="props.row.rate" class="text-small-85">
            {{ props.row.rate.name }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="row justify-center">
            <InvoiceInfoBtn :invoice="props.row" edit @success="reload" />
            <ReceiptForInvoiceBtn :invoice-id="props.row.id" />
          </div>
        </q-td>
      </template>
    </q-table>

  </div>
</template>

<script>
import { defineComponent } from 'vue'
import InvoiceInfoBtn from 'src/Modules/Bookkeeping/components/Invoice/InvoiceInfo/Dialog.vue'
import ReceiptForInvoiceBtn from 'src/Modules/Receipt/components/ReceiptForInvoiceBtn/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'

export default defineComponent({
  components: {
    InvoiceInfoBtn,
    ShowPrice,
    ReceiptForInvoiceBtn
  },
  props: {
    list: {
      type: Array,
      default: () => []
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
        name: 'stead',
        align: 'center',
        label: 'Участок'
      },
      {
        name: 'title',
        align: 'center',
        label: 'Назначение'
      },
      {
        name: 'price',
        align: 'center',
        label: 'Сумма'
      },
      {
        name: 'rate',
        align: 'center',
        label: 'Тип'
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
