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
        </q-td>
      </template>
      <template v-slot:body-cell-title="props">
        <q-td :props="props">
          {{ props.row.raw_data[4] }}
        </q-td>
      </template>
      <template v-slot:body-cell-group="props">
        <q-td :props="props">
          {{ props.row.rate?.name }}
        </q-td>
      </template>
      <template v-slot:body-cell-price="props">
        <q-td :props="props" auto-width>
          <div>
            <ShowPrice :price="props.row.price" hide-icon />
          </div>
          <div v-if="props.row.error" class="text-red text-small-85">
            Ошибка
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="row justify-center">
            <PaymentInfo :payment="props.row" />
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
import PaymentInfo from './PaymentInfo.vue'

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
        name: 'stead',
        align: 'center',
        label: 'Участок'
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
