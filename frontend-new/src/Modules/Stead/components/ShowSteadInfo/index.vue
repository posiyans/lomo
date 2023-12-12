<template>
  <div>
    <slot v-bind:item="name">
      {{ name }}
    </slot>
  </div>
</template>

<script>
import { computed, defineComponent, onBeforeMount, watch } from 'vue'
import { getSteadInfo } from 'src/Modules/Stead/api/stead'
import { steads } from 'src/Modules/Stead/hooks/stead_info'

export default defineComponent({
  props: {
    steadId: {
      type: [Number, String],
      required: true
    },
    format: {
      type: String,
      default: 'n' // n - номер, s - размер
    }
  },
  setup(props) {
    const stead = computed(() => {
      return steads.value[props.steadId] ?? {}
    })
    const name = computed(() => {
      if (props.format === 's') {
        return stead.value.size || ''
      }
      return stead.value.number || ''
    })
    const getData = () => {
      const data = {
        id: props.steadId
      }
      getSteadInfo(data)
        .then(res => {
          steads.value[props.steadId] = res.data.data
        })
    }

    onBeforeMount(() => {
      if (!steads.value[props.steadId] && steads.value[props.steadId] !== false) {
        steads.value[props.steadId] = false
        getData()
      }
    })
    watch(
      () => props.steadId,
      () => {
        if (!steads.value[props.steadId] && steads.value[props.steadId] !== false) {
          steads.value[props.steadId] = false
          getData()
        }
      }
    )
    return {
      stead,
      name
    }
  }
})
</script>

<style scoped>

</style>
