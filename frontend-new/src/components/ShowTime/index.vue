<template>
  <component :is="block">{{ before }}{{ showTime }}{{ after }}</component>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { date } from 'quasar'

export default defineComponent({
  components: {},
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
    },
    after: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const int_time = computed(() => {
      return parseInt(+props.time, 10)
    })
    const sql_time = computed(() => {
      return !int_time.value && typeof props.time === 'string' && props.time.length > 16 && props.time[4] === '-' && props.time[7] === '-' && (props.time[10] === 'T' || props.time[10] === ' ') && props.time[13] === ':' && props.time[16] === ':'
    })
    const date_format = computed(() => {
      return props.time[4] === '-' && props.time[7] === '-' && props.time.length === 10
    })
    const dateUtc = computed(() => {
      console.log(props.time)
      if (props.time) {
        if (sql_time.value) {
          return date.extractDate(props.time.replace(/T/g, ' ').substring(0, 19), 'YYYY-MM-DD HH:mm:ss')
        } else if (date_format.value) {
          return date.extractDate(props.time.replace(/T/g, ' ').substring(0, 10), 'YYYY-MM-DD')
        }
      }
      return ''
    })
    const showTime = computed(() => {
      if (dateUtc.value) {
        console.log(dateUtc.value)
        const offset = dateUtc.value.getTimezoneOffset()
        const newDate = date.subtractFromDate(dateUtc.value, { minutes: offset })
        console.log(newDate)
        return date.formatDate(newDate, props.format)
      }
      return ''
    })


    return {
      data,
      showTime
    }
  }
})
</script>

<style scoped>

</style>
