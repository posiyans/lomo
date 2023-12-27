<template>
  <div>
    <q-table
      flat bordered
      :rows="rate"
      :columns="columns"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
      wrap-cells
      separator="cell"
      row-key="id"
    >
      <template v-slot:body-cell-number="props">
        <q-td :props="props" auto-width>
          <div>
            {{ props.row.id }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-name="props">
        <q-td :props="props">
          <div class="row items-center q-col-gutter-sm">
            <div>
              {{ props.row.name }}
            </div>
            <div>
              <q-chip outline square dense>
                {{ props.row.description }}
              </q-chip>
            </div>
          </div>
          <div v-if="edit" class="absolute-top-right">
            <EditRateType :rate="props.row" @success="getData">
              <q-btn icon="more_horiz" flat dense color="grey" />
            </EditRateType>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-rate="props">
        <q-td :props="props">
          <div>
            <div>
              {{ props.row.rate.description }}
            </div>
            <div class="text-grey text-small-80 row items-center q-col-gutter-xs justify-center">
              <div>
                от
              </div>
              <ShowTime :time="props.row.rate.date_start" format="DD-MM-YYYY" />
            </div>
            <EditRate v-if="edit" :rate="props.row" @success="getData" class="absolute-top-right">
              <q-btn icon="more_horiz" flat dense color="grey" />
            </EditRate>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-status="props">
        <q-td :props="props">
          <q-chip square :color="props.row.enable ? 'teal' : 'red'" outline text-color="white">
            {{ props.row.enable ? 'Действующий' : 'Не действующий' }}
          </q-chip>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getRateListForGroup } from 'src/Modules/Rate/api/rateAdminApi'
import ShowTime from 'components/ShowTime/index.vue'
import EditRate from 'src/Modules/Rate/components/EditRate/index.vue'
import EditRateType from 'src/Modules/Rate/components/EditRateType/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    EditRate,
    EditRateType
  },
  props: {
    rateGroupId: {
      type: [String, Number],
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const rate = ref([])
    const columns = [
      {
        name: 'number',
        align: 'center',
        label: 'ID',
      },
      {
        name: 'name',
        align: 'left',
        label: 'Назначение платежа',
      },
      {
        name: 'rate',
        align: 'center',
        label: 'Тариф',
      }
    ]
    const getData = () => {
      getRateListForGroup(props.rateGroupId)
        .then(res => {
          rate.value = res.data.data
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      rate,
      getData,
      columns
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
