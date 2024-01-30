<template>
  <div>
    <FilterBlock v-model="listQuery" class="q-mb-xs" />
    <TableBlock :list="list" :edit="edit" @reload="reload" />
    <LoadMore :key="key" v-model:listQuery="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import FilterBlock from './components/FilterBlock/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import { getInstrumentReadingList } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'
import TableBlock from './components/TableBlock/index.vue'

export default defineComponent({
  components: {
    FilterBlock,
    LoadMore,
    TableBlock

  },
  props: {
    steadId: {
      type: [String, Number],
      default: ''
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const func = getInstrumentReadingList
    const listQuery = ref({
      stead_id: props.steadId,
      rate_group_id: '',
      rate_type_id: '',
      date_start: '',
      date_end: ''
    })
    const reload = () => {
      key.value++
    }
    const setList = (val) => {
      list.value = val
    }
    return {
      key,
      reload,
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
