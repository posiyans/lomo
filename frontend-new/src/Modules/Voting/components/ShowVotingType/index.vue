/* eslint-disable */
<template>
  <div :class="className">
    {{name}}
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref, computed, reactive } from 'vue'
import { useQuasar } from 'quasar'
import { votingType } from 'src/Modules/Voting/constant.js'

export default defineComponent({
  props: {
    type: {
      type: String,
      required: true
    },
    color: {
      type: Boolean,
      default: false
    }
  },
  setup ({ type, color }) {
    const $q = useQuasar()
    const obj = computed(() => {
      return votingType.find(item => {
        return item.value === type
      })
    })
    const name = computed(() => {
      if (obj) {
        return obj.value.label
      }
      return type
    })
    const className = computed(() => {
      if (color && obj) {
        return 'text-' + obj.value.color
      }
      return ''
    })
    return {
      name,
      className
    }
  }
})
</script>

<style scoped>

</style>
