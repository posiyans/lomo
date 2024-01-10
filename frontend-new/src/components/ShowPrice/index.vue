<template>
  <div>
    {{ formatedPrice }}
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    price: {
      type: [String, Number],
      default: ''
    },
    hideIcon: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const opt = computed(() => {
      if (props.hideIcon) {
        return {}
      } else {
        return { style: 'currency', currency: 'RUB' }
      }
    })
    const formatedPrice = computed(() => {
      return new Intl.NumberFormat('ru-RU', opt.value).format(props.price)
    })
    return {
      data,
      formatedPrice
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
