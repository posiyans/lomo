<template>
  <div>
    <ShowRateForGroup :rate-group-id="rateGroup.id" edit @success="success" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import ShowRateForGroup from 'src/Modules/Rate/components/ShowRateForGroup/index.vue'

export default defineComponent({
  components: {
    ShowRateForGroup
  },
  props: {
    rateGroup: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const success = () => {
      emit('success')
    }
    return {
      data,
      success
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
