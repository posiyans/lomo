<template>
  <component :is="block">{{ time_show }}</component>
</template>
<script>
export default {
  props: {
    time: {
    },
    format: {
      type: String,
      default: 'DD-MM-YYYY HH:mm'
    },
    block: {
      type: String,
      default: 'div'
    }
  },
  data() {
    return {
    }
  },
  computed: {
    int_time() {
      return parseInt(+this.time, 10)
    },
    sql_time() {
      if (!this.int_time && typeof this.time === 'string' && this.time.length > 16 && this.time[4] === '-' && this.time[7] === '-' && this.time[10] === 'T' && this.time[13] === ':' && this.time[16] === ':') {
        return true
      }
      return false
    },
    date_format() {
      if (this.time[4] === '-' && this.time[7] === '-' && this.time.length === 10) {
        return true
      }
      return false
    },
    time_show() {
      if (this.time) {
        if (this.sql_time || this.date_format) {
          return this.$moment(this.time).format(this.format)
        } else if (this.$moment.unix(this.int_time)) {
          return this.$moment.unix(this.int_time).format(this.format)
        }
      }
      return ''
    }
  }
}
</script>

<style scoped>
</style>
