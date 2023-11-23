<template>
  <component :is="block">
    {{ publicTime }}
  </component>
</template>

<script>
export default {
  props: {
    time: {
      type: [String, Number],
      required: false
    },
    block: {
      type: String,
      default: 'div'
    }
  },
  computed: {
    publicTime() {
      const time = (this.$moment() - this.$moment(this.time)) / 1000
      if (time > 24 * 3600 * 10) {
        return this.$moment(this.time).format('DD MMMM YYYY')
      }
      return this.$moment(this.time).format('DD MMMM в HH:mm')
    }
  }
}
</script>

<style scoped>

</style>
