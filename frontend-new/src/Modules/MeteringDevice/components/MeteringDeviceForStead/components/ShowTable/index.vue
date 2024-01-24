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
      <template v-slot:body-cell-rate="props">
        <q-td :props="props">
          <div>
            {{ props.row.rate.group_name }} {{ props.row.rate.name }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-device="props">
        <q-td :props="props">
          <div>
            {{ props.row.device_brand }}
            <span class="text-primary">
              Sn: {{ props.row.serial_number }}
            </span>
          </div>
          <div class="row items-center q-col-gutter-xs justify-center text-grey">
            <div v-if="props.row.installation_date">
              с
            </div>
            <ShowTime :time="props.row.installation_date" format="DD-MM-YYYY" />
            <div v-if="props.row.verification_date">
              по
            </div>
            <ShowTime :time="props.row.verification_date" format="DD-MM-YYYY" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-init_value="props">
        <q-td :props="props">
          {{ props.row.initial_data }} {{ props.row.rate.unit_name }}
        </q-td>
      </template>
      <template v-slot:body-cell-last_value="props">
        <q-td :props="props">
          <div>
            {{ props.row.last_reading.value }} {{ props.row.rate.unit_name }}
          </div>
          <div class="row items-center q-col-gutter-xs text-grey justify-center">
            <div>
              от
            </div>
            <ShowTime :time="props.row.last_reading.created_at" format="DD-MM-YYYY" class="text-grey" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-desc="props">
        <q-td :props="props">
          <div class="ellipsis">
            {{ props.row.description }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="row items-center q-col-gutter-xs">
            <div>
              <q-btn color="secondary" label="Подать показания" />
            </div>
            <div v-if="edit" class="q-gutter-sm">
              <MeteringDeviceEdit :device="props.row" @close="reload" />
            </div>
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import MeteringDeviceEdit from 'src/Modules/MeteringDevice/components/MeteringDeviceEdit/Dialog.vue'

export default defineComponent({
  components: {
    ShowTime,
    MeteringDeviceEdit
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
    const columns = [
      {
        name: 'rate',
        align: 'center',
        label: 'Тип'
      },
      {
        name: 'device',
        align: 'center',
        label: 'Прибор'
      },
      {
        name: 'init_value',
        align: 'center',
        label: 'Начальные показания'
      },
      {
        name: 'last_value',
        align: 'center',
        label: 'Последние показания'
      },
      {
        name: 'desc',
        align: 'center',
        label: 'Примечание',
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
      data,
      reload,
      columns
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

