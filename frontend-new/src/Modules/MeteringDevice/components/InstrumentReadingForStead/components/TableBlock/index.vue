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
          <ShowTime :time="props.row.created_at" format="DD-MM-YYYY" class="" />
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
        <q-td :props="props" :class="{ 'o-60': !props.row.active }">
          {{ props.row.value }} {{ props.row.device.rate.unit_name }}
        </q-td>
      </template>
      <template v-slot:body-cell-last_value="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          \
        </q-td>
      </template>
      <template v-slot:body-cell-desc="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          <div class="ellipsis">
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props" :class="{ 'o-60': !props.row.device.active }">
          <div class="row items-center q-col-gutter-xs">
            <div>
              <q-btn color="secondary" label="Подать показания" />
            </div>
            <div v-if="edit" class="q-gutter-sm">
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

