<template>
  <div>
    <q-table
      v-if="list.length > 0"
      flat
      bordered
      :dense="dense"
      :rows="list"
      :columns="columns"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
      wrap-cells
      separator="cell"
      no-data-label="Платежи не найдены"
      row-key="id"
    >
      <template v-slot:body-cell-id="props">
        <q-td :props="props" auto-width class="q-pa-none text-center">
          {{ props.row.id }}
        </q-td>
      </template>
      <template v-slot:body-cell-date="props">
        <q-td :props="props" auto-width>
          <ShowTime :time="props.row.payment_date" format="DD-MM-YYYY" />
        </q-td>
      </template>
      <template v-slot:body-cell-stead="props">
        <q-td :props="props" auto-width>
          <router-link v-if="props.row.stead" :to="`/admin/stead/info/${props.row.stead.id}`">
            <q-chip outline square color="primary" text-color="white">
              {{ props.row.stead.number }}
            </q-chip>
          </router-link>
          <div v-if="props.row.error && props.row.stead?.number !== props.row?.raw_data[2]" class="text-grey">
            {{ props.row?.raw_data[2] }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-title="props">
        <q-td :props="props">
          <div class="text-small-75">
            {{ props.row.raw_data[4] }}
          </div>
          <div v-if="props.row.description" class="absolute-bottom-right text-grey">
            <q-icon name="expand_circle_down">
              <q-tooltip>
                {{ props.row.description }}
              </q-tooltip>
            </q-icon>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-group="props">
        <q-td :props="props">
          {{ props.row.rate?.name }}
        </q-td>
      </template>
      <template v-slot:body-cell-price="props">
        <q-td :props="props" auto-width>
          <div class="text-no-wrap">
            <ShowPrice :price="props.row.price" />
          </div>
          <div v-if="props.row.error" class="text-red text-small-85">
            Требует уточнения
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-more="props">
        <q-td :props="props">
          <div class="row justify-center">
            <PaymentInfo :payment="props.row" :edit="edit" @reload="reload" @deletePayment="reload" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-select="props">
        <q-td :props="props">
          <div class="row justify-center">
            <q-btn flat color="primary" icon="add" dense @click="selectRow(props.row)" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import PaymentInfo from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/Dialog.vue'

export default defineComponent({
  components: {
    PaymentInfo,
    ShowTime,
    ShowPrice
  },
  props: {
    list: {
      type: Array,
      default: () => []
    },
    dense: {
      type: Boolean,
      default: false
    },
    edit: {
      type: Boolean,
      default: false
    },
    columns: {
      type: Array,
      default: () => []
    }
  },
  setup(props, { emit }) {
    // const columns = [
    //   {
    //     name: 'id',
    //     align: 'center',
    //     label: '№'
    //   },
    //   {
    //     name: 'date',
    //     align: 'center',
    //     label: 'Дата'
    //   },
    //   {
    //     name: 'price',
    //     align: 'center',
    //     label: 'Сумма, руб'
    //   },
    //
    //   {
    //     name: 'title',
    //     align: 'left',
    //     label: 'Назначение'
    //   },
    //   {
    //     name: 'stead',
    //     align: 'center',
    //     label: 'Участок'
    //   },
    //   {
    //     name: 'group',
    //     align: 'center',
    //     label: 'Группа'
    //   },
    //   {
    //     name: 'actions',
    //     align: 'center',
    //     label: ''
    //   }
    // ]
    const reload = () => {
      emit('reload')
    }
    const selectRow = (data) => {
      console.log(data)
      emit('selected', data)
    }
    return {
      selectRow,
      reload
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
