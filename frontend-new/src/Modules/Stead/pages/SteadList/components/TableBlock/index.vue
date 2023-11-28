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
      <template v-slot:body-cell-number="props">
        <q-td :props="props" auto-width>
          <div class="my-table-details">
            {{ props.row.number }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-size="props">
        <q-td :props="props" auto-width>
          <div class="my-table-details">
            {{ props.row.size }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="q-gutter-sm">
            <q-btn label="Инфо" color="primary" :to="`/admin/bookkeping/billing_balance_stead/${props.row.id}`" />
            <q-btn label="Квит." color="primary" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    list: {
      type: Array,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const columns = [
      {
        name: 'number',
        align: 'center',
        label: '№',
      },
      {
        name: 'size',
        align: 'center',
        label: 'Размер, кв.м',
      },
      {
        name: 'owner',
        align: 'center',
        label: 'Собственник',
      },
      {
        name: 'balans',
        align: 'center',
        label: 'Баланс',
      },
      {
        name: 'actions',
        align: 'center',
        label: '',
      }
    ]
    return {
      data,
      columns
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
