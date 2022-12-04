<template>
  <component :is="block">
    {{ title }}
  </component>
</template>

<script>
import { declOfNum } from 'src/utils/helper.js'

export default {
  props: {
    number: {
      type: [String, Number],
      required: true
    },
    titles: {
      type: Array,
      required: true
    },
    block: {
      type: String,
      default: 'span'
    }
  },
  computed: {
    title() {
      return declOfNum(this.number, this.titles)
    }
  }
}
</script>
