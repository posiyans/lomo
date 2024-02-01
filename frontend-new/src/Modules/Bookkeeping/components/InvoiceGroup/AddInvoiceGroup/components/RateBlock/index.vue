<template>
  <div>
    <div class="q-pa-sm text-primary text-weight-bold">
      Начислить
    </div>
    <div class="row">
      <q-markup-table
        square
      >
        <tbody>
        <tr v-for="item in rateType" :key="item.id">
          <td>
            {{ item.name }}
          </td>
          <td>
            {{ item.rate.description }}
          </td>
          <td>
            <q-checkbox v-model="item.selected" />
          </td>
        </tr>
        </tbody>
      </q-markup-table>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    rateType: {
      type: Object,
      default: ''
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const selected = ref([])
    const rate = ref([])
    return {
      data,
      selected,
      rate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
