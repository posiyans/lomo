<template>
  <div>
    <table v-if="stead">

      <tr>
        <td>
          id
        </td>
        <td>
          {{ stead.id }}
        </td>
        <td />
      </tr>
      <TrTableBlock
        v-for="item in columns"
        :key="item.field"
        v-model="stead"
        :readonly="item.readonly"
        :label="item.label"
        :field="item.field"
        @reload="getData"
      />
    </table>
    <pre>
    {{ stead }}

    </pre>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getSteadInfo } from 'src/Modules/Stead/api/stead'
import TrTableBlock from './components/TrTableBlock/index.vue'

export default defineComponent({
  components: {
    TrTableBlock
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const columns = [
      {
        label: 'Номер',
        field: 'number',
        type: 'number'
      },
      {
        label: 'Размер',
        field: 'size',
        type: 'number'
      },
      {
        label: 'Кадастровый номер',
        field: 'kadastr',
        type: 'string'
      },
    ]
    const data = ref(false)
    const stead = ref(null)
    const getData = () => {
      const data = {
        id: props.steadId,
        full: 1
      }
      getSteadInfo(data)
        .then(res => {
          stead.value = res.data.data
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      getData,
      columns,
      stead
    }
  }
})
</script>

<style scoped lang='scss'>
table {
  border-collapse: collapse;
  margin: 25px;
}

td, th {
  border: 1px solid #606266;
  padding: 5px 10px;
  text-align: center;
  color: #000000;
}
</style>
