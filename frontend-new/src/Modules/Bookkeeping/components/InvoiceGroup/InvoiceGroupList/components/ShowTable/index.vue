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
      <template v-slot:body-cell-title="props">
        <q-td :props="props">
          <div class="row items-center q-col-gutter-sm">
            <div>
              {{ props.row.title }}
            </div>
            <ShowTime :time="props.row.created_at" format="DD-MM-YYYY" class="text-grey" />
            <div class="text-grey">
              <div>
                Выставлено: {{ props.row.invoices.total }}<span class="text-teal">({{ props.row.invoices.paid.total }})</span>
                <DecOfNum :number="props.row.invoices.total" :titles="['счет', 'счета', 'счетов']" />
              </div>
              <div class="row">
                <ShowPrice :price="props.row.invoices.price" before-text="на общую сумму " />
                <ShowPrice :price="props.row.invoices.paid.price" before-text="(" append-text=")" class="text-teal" />
              </div>
            </div>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-description="props">
        <q-td :props="props">
          <div class="text-small-75">
            <div v-for="item in props.row.options" :key="item.id">
              {{ item.name }}
              {{ item.rate?.description }}
            </div>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="row justify-center">
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
import DecOfNum from 'components/DecOfNum/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    ShowPrice,
    DecOfNum
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
        name: 'title',
        align: 'left',
        label: 'Назначение'
      },
      {
        name: 'description',
        align: 'center',
        label: 'Описание'
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
