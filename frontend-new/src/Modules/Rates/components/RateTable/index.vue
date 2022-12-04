<template>
  <div>
    <q-table
        :loading="loading"
        :rows="list"
        :columns="columns"
        row-key="name"
        wrap-cells
        separator="cell"
        hide-bottom
        :pagination="{ rowsPerPage: 0 }"
    >
      <template v-slot:body-cell-date="props">
        <q-td :props="props">
          <ShowTime :time="props.row.rate.date_start" format="DD-MM-YYYY" />
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>

const columns = [
  {
    name: 'name',
    required: true,
    label: 'Название',
    align: 'left',
    field: row => row.name,
    format: val => `${val}`
  },
  {
    name: 'rate',
    required: true,
    label: 'Тариф',
    align: 'center',
    field: row => row.rate.description,
    format: val => `${val}`
  },
  {
    name: 'date',
    required: true,
    label: 'Дата начала действия',
    align: 'center',
    field: row => row.rate.date_start,
    format: val => `${val}`
  }

]
import { fetchList } from 'src/Modules/Rates/api/rates.js'
import { onBeforeMount, ref } from 'vue'
import ShowTime from 'src/components/ShowTime/index.vue'
export default {
  components: {
    ShowTime
  },
  props: {
    type: {
      type: String,
      default: '1'
    }
  },
  setup (props) {
    const list = ref([])
    const loading = ref(true)
    const listQuery = ref({
      type: props.type
    })
    const getList = () => {
      loading.value = true
      fetchList(listQuery.value).then(response => {
        if (response.data.status) {
          list.value = response.data.data
        }
        loading.value = false
      })
    }
    onBeforeMount(() => {
      getList()
    })
    return {
      columns,
      list,
      loading,
      props
    }
  }
}

</script>
