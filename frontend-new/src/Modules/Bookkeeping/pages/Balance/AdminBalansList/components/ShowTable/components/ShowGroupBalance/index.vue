<template>
  <ShowPrice :price="price" :class="className" />
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import ShowPrice from 'components/ShowPrice/index.vue'

export default defineComponent({
  components: {
    ShowPrice
  },
  props: {
    rates: {
      type: Array,
      default: () => []
    },
    rateGroupId: {
      type: [Number, String],
      default: ''
    }
  },
  setup(props, { emit }) {
    const currentRateGroup = computed(() => {
      return props.rates.find(item => item.id === props.rateGroupId)
    })
    const price = computed(() => {
      if (currentRateGroup.value) {
        return currentRateGroup.value.price
      }
    })
    const className = computed(() => {
      if (price.value > 0) {
        return 'text-teal'
      }
      if (price.value < 0) {
        return 'text-red'
      }
      return ''

    })
    return {
      price,
      className
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
