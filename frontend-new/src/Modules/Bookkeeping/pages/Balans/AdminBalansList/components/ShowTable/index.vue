<template>
  <div>
    <q-table
      flat
      bordered
      :rows="list"
      :columns="columnsDinamic"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
      wrap-cells
      separator="cell"
      row-key="id"
    >
      <template v-slot:body="props">
        <q-tr :props="props" :key="`m_${props.row.index}`">
          <q-td auto-width>
            <div class="text-center">
              <div>
                <q-chip outline square color="primary" text-color="white">
                  {{ props.row.stead.number }}
                </q-chip>
              </div>
              <div class="text-small-85 text-grey">
                {{ props.row.stead.size }} m<sup>2</sup>
              </div>
            </div>
          </q-td>
          <q-td>
            <ShowPrice :price="props.row?.price" class="text-no-wrap text-center" />
          </q-td>
          <q-td v-for="item in rateGroup" :key="item.id">
            <ShowGroupBalance :rates="props.row.rates" :rate-group-id="item.id" class="text-center text-no-wrap" />
          </q-td>
          <q-td>
            <div v-if="props.row.last_pay" class="text-center text-grey text-small-85">
              <div>
                {{ props.row.last_pay.payment_date }}
              </div>
              <div>
                {{ props.row.last_pay.raw_data[4] }}

              </div>
              <ShowPrice :price="props.row.last_pay.price" class="text-no-wrap" />
            </div>
          </q-td>
          <q-td>
            <div class="row justify-center">
              <q-btn label="Подробнее" color="primary" :to="'/admin/stead/info/' + props.row.stead.id + '?tab=payment'" />
            </div>
          </q-td>
        </q-tr>

      </template>
    </q-table>

  </div>
</template>

<script>
import { computed, defineComponent, onMounted, ref } from 'vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import { getRateGroupList } from 'src/Modules/Rate/api/rateAdminApi'
import ShowGroupBalance from './components/ShowGroupBalance/index.vue'

export default defineComponent({
  components: {
    ShowPrice,
    ShowGroupBalance
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
    const rateGroup = ref([])
    const getData = () => {
      getRateGroupList()
        .then(response => {
          rateGroup.value = response.data.data
        })
    }
    onMounted(() => {
      getData()
    })
    const columnsDinamic = computed(() => {
      const tmp =
        [
          {
            name: 'stead',
            align: 'center',
            label: 'Участок'
          },
          {
            name: 'balance',
            align: 'center',
            label: 'Баланс'
          }
        ]
      rateGroup.value.forEach(item => {
        tmp.push({
          name: 'rate' + item.id,
          align: 'center',
          label: item.name
        })
      })
      tmp.push({
        name: 'lastpay',
        align: 'center',
        label: 'Последний платеж'
      })
      tmp.push({
        name: 'actions',
        align: 'center',
        label: ''
      })
      return tmp
    })
    const reload = () => {
      emit('reload')
    }
    return {
      rateGroup,
      columnsDinamic,
      reload
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
