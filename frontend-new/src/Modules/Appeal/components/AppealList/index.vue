<template>
  <div>
    <div class="row no-wrap justify-between q-pb-sm">
      <DropDownBlock only-mobile :max-width="580">
        <FilterBlock v-model="listQuery" />
      </DropDownBlock>
      <slot></slot>
    </div>
    <TableBlock :list="list" :add-fio="!userUid" />
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import LoadMore from 'src/components/LoadMore/index.vue'
import { fetchAppealList } from 'src/Modules/Appeal/api/appealApi'
import FilterBlock from './components/FilterBlock/index.vue'
import TableBlock from './components/TableBlock/index.vue'
import DropDownBlock from 'components/DropDownBlock/index.vue'

export default defineComponent({
  components: {
    DropDownBlock,
    LoadMore,
    FilterBlock,
    TableBlock
  },
  props: {
    userUid: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const list = ref([])
    const key = ref(1)
    const listQuery = ref({
      page: 1,
      limit: 20,
      find: '',
      type: '',
      status: 'open',
      user_uid: props.userUid
    })
    const router = useRouter()
    const route = useRoute()
    const func = fetchAppealList
    const reloadData = (val) => {
      key.value++
    }
    const setList = (val) => {
      list.value = val
    }
    return {
      list,
      key,
      listQuery,
      reloadData,
      setList,
      func
    }
  }
})
</script>

<style scoped>

</style>
