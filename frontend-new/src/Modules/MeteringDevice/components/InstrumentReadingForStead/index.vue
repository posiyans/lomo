<template>
  <div>
    <FilterBlock v-model="listQuery" />
    {{ listQuery }}
    {{ list }}
    <LoadMore v-model:listQuery="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import FilterBlock from './components/FilterBlock/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import { getInstrumentReadingList } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'

export default defineComponent({
  components: {
    FilterBlock,
    LoadMore
  },
  props: {
    steadId: {
      type: [String, Number],
      default: ''
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const list = ref([])
    const func = getInstrumentReadingList
    const listQuery = ref({
      stead_id: props.steadId,
      rate_group_id: '',
      rate_type_id: '',
      date_start: '',
      date_end: ''
    })
    const setList = (val) => {
      list.value = val
    }
    return {
      data,
      list,
      listQuery,
      func,
      setList
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
