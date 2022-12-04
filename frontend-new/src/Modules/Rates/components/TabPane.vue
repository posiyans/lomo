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
    />
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
  }

]
import { fetchList } from 'src/Modules/Rates/api/rates.js'
import { onBeforeMount, ref } from 'vue'

export default {
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
