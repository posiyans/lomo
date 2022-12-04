<template>
  <component :is="block">{{ time_show }}</component>
</template>

<script>
export default {
  props: {
    time: {
    },

    block: {
      type: String,
      default: 'div'
    }
  },
  data () {
    return {
    }
  },
  computed: {
    hour () {
      return Math.floor(this.time / 3600)
    },
    minut () {
      return this.time % 3600 / 60
    },

    sql_time () {
      if (!this.int_time && typeof this.time === 'string' && this.time.length > 16 && this.time[4] === '-' && this.time[7] === '-' && this.time[10] === 'T' && this.time[13] === ':' && this.time[16] === ':') {
        return true
      }
      return false
    },
    date_format () {
      if (this.time[4] === '-' && this.time[7] === '-' && this.time.length === 10) {
        return true
      }
      return false
    },
    time_show () {
      let text = ''
      if (this.hour) {
        text += this.hour + ' ч '
      }
      if (this.minut) {
        text += this.minut + ' м '
      }
      return text
    }
  }
}
</script>

<style scoped>
</style>
