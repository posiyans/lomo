<template>
  <div class="row items-center q-col-gutter-xs">
    <div :class="{ 'text-red-10' : balance.price < 0 }">
      Баланс:
    </div>
    <ShowPrice :price="balance.price" :class="{ 'text-red-10' : balance.price < 0 }" />
    <div class="row items-center q-col-gutter-xs">
      <div v-for="item in balance.rates" :key="item.id" class="row items-center q-col-gutter-xs" :class="{ 'text-red-10' : item.price < 0 }">
        <div>
          {{ item.name }}:
        </div>
        <ShowPrice :price="item.price" />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getBalanceList } from 'src/Modules/Bookkeeping/api/balaceApi'
import ShowPrice from 'components/ShowPrice/index.vue'

export default defineComponent({
  components: {
    ShowPrice
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const listQuery = ref({
      stead_id: props.steadId,
      page: 1,
      limit: 1
    })
    const balance = ref({})
    const getData = () => {
      getBalanceList(listQuery.value)
        .then(res => {
          if (res.data.data && res.data.data.length === 1) {
            balance.value = res.data.data[0]
          }
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      balance
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
