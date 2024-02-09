<template>
  <div>
    <q-table
      flat bordered
      :rows="list"
      :columns="columns"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
      wrap-cells
      separator="cell"
      row-key="id"
    >
      <template v-slot:body-cell-date="props">
        <q-td :props="props">
          <ShowTime :time="props.row.date" format="DD-MM-YYYY" class="" />
          {{ props.row.device.rate.name }}
        </q-td>
      </template>
      <template v-slot:body-cell-rate="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          <div :class="{ 'text-grey': !props.row.device.active }">
            {{ props.row.device.rate.group_name }} {{ props.row.device.rate.name }}
          </div>
          <div v-if="!props.row.device.active" class="text-red">
            Не активный
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-device="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          <div>
            {{ props.row.device.device_brand }}
            <span class="text-primary">
              Sn: {{ props.row.device.serial_number }}
            </span>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-init_value="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          <div class="text-weight-bold">
            {{ props.row.value }} {{ props.row.device.rate.unit_name }}
          </div>
          <div class="text-small-85 text-grey">
            {{ props.row.previous_value }} {{ props.row.device.rate.unit_name }}
          </div>

        </q-td>
      </template>
      <template v-slot:body-cell-last_value="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          {{ props.row.previous_value }} {{ props.row.device.rate.unit_name }}
        </q-td>
      </template>
      <template v-slot:body-cell-delta="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          <div class="ellipsis">
            {{ props.row.delta }} {{ props.row.device.rate.unit_name }}
          </div>
          <div class="text-small-85 text-grey">
            {{ props.row.rate.description }}
          </div>
          <div>
            <ShowPrice :price="props.row.cost" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-desc="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          <div v-if="props.row.invoice" class="cursor-pointer text-red-10">
            <InvoiceInfo :invoice="props.row.invoice" :edit="edit">
              Счет № {{ props.row.invoice.id }}
            </InvoiceInfo>
          </div>
          <div v-if="props.row.payment" class="cursor-pointer text-teal">
            <PaymentInfoShowAndEdit :payment="props.row.payment" :edit="edit">
              Платеж № {{ props.row.payment.id }}
            </PaymentInfoShowAndEdit>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props" auto-width :class="{ 'o-60': !props.row.device.active }">
          <div class="row items-center q-col-gutter-xs">
            <div v-if="edit" class="q-gutter-sm">
              <DeleteInstrumentReading v-if="!props.row.invoice" :reading-id="props.row.id" @success="reload" />
            </div>
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import MeteringDeviceEdit from 'src/Modules/MeteringDevice/components/MeteringDeviceEdit/Dialog.vue'
import PaymentInfoShowAndEdit from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/Dialog.vue'
import InvoiceInfo from 'src/Modules/Bookkeeping/components/Invoice/InvoiceInfo/Dialog.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import DeleteInstrumentReading from 'src/Modules/MeteringDevice/components/DeleteInstrumentReading/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    MeteringDeviceEdit,
    PaymentInfoShowAndEdit,
    DeleteInstrumentReading,
    InvoiceInfo,
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
    const data = ref(false)
    const columns = computed(() => {
      const tmp = [
        {
          name: 'date',
          align: 'center',
          label: 'Дата'
        },
        {
          name: 'device',
          align: 'center',
          label: 'Прибор'
        },
        {
          name: 'init_value',
          align: 'center',
          label: 'Показания'
        },
        {
          name: 'delta',
          align: 'center',
          label: 'К оплате'
        },
        {
          name: 'desc',
          align: 'center',
          label: 'Примечание',
        }
      ]
      if (props.edit) {
        tmp.push({
          name: 'actions',
          align: 'center',
          label: ''
        })
      }
      return tmp
    })
    const reload = () => {
      emit('reload')
    }
    return {
      data,
      reload,
      columns
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

