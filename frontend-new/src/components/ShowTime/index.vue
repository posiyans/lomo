<template>
  <component :is="block">{{ before }} {{ time_show }}</component>
</template>
<script>
import { date } from 'quasar'

export default {
  props: {
    time: {},
    format: {
      type: String,
      default: 'DD-MM-YYYY HH:mm'
    },
    block: {
      type: String,
      default: 'div'
    },
    before: {
      type: String,
      default: ''
    }

  },
  data() {
    return {}
  },
  computed: {
    int_time() {
      return parseInt(+this.time, 10)
    },
    sql_time() {
      return !this.int_time && typeof this.time === 'string' && this.time.length > 16 && this.time[4] === '-' && this.time[7] === '-' && (this.time[10] === 'T' || this.time[10] === ' ') && this.time[13] === ':' && this.time[16] === ':'
    },
    date_format() {
      return this.time[4] === '-' && this.time[7] === '-' && this.time.length === 10
    },
    time_show() {
      if (this.time) {
        if (this.sql_time) {
          const tmp = date.extractDate(this.time.replace(/T/g, ' ').substring(0, 19), 'YYYY-MM-DD HH:mm:ss')
          return date.formatDate(tmp, this.format)
        } else if (this.date_format) {
          const tmp = date.extractDate(this.time.replace(/T/g, ' ').substring(0, 10), 'YYYY-MM-DD')
          return date.formatDate(tmp, this.format)
        }
      }
      return ''
    }
  }
}
</script>

<style scoped>
</style>
