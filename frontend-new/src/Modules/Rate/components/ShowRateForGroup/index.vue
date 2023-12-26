<template>
  <div>
    {{ rate }}
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getRateListForGroup } from 'src/Modules/Rate/api/rateAdminApi'

export default defineComponent({
  components: {},
  props: {
    rateGroupId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const rate = ref([])
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
      rate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
