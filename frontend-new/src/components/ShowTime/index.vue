<template>
  <component :is="block">{{ before }}{{ showTime }}{{ after }}</component>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import { date } from 'quasar'
import { declOfNum } from 'src/utils/helper'

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
    from: {
      type: Boolean,
      default: false
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
    const unixTime = computed(() => {
      return parseInt(+props.time, 10)
    })
    const sql_time = computed(() => {
      return !unixTime.value && typeof props.time === 'string' && props.time.length > 16 && props.time[4] === '-' && props.time[7] === '-' && (props.time[10] === 'T' || props.time[10] === ' ') && props.time[13] === ':' && props.time[16] === ':'
    })
    const date_format = computed(() => {
      return props.time[4] === '-' && props.time[7] === '-' && props.time.length === 10
    })
    const dateUtc = computed(() => {
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
      if (props.from) {
        return from()
      } else {
        if (dateUtc.value) {
          const offset = dateUtc.value.getTimezoneOffset()
          const newDate = date.subtractFromDate(dateUtc.value, { minutes: offset })
          return date.formatDate(newDate, props.format)
        }
        return ''
      }
    })

    const from = () => {
      const dateNow = new Date()
      if (dateUtc.value) {
        // const date2 = new Date(dateUtc.value)
        const offset = dateUtc.value.getTimezoneOffset()
        const date2 = new Date(date.subtractFromDate(dateUtc.value, { minutes: offset }))
        const sec = date.getDateDiff(dateNow, date2, 'second')
        const minute = Math.floor(sec / 60)
        const hour = Math.floor(minute / 60)
        const day = Math.floor(hour / 24)
        const month = (date.getDateDiff(dateNow, date2, 'month') - 1)
        if (month > 24) {
          return 'больше 2 лет назад'
        } else if (month > 12) {
          return 'больше года назад'
        } else if (month > 0) {
          return month + ' ' + declOfNum(month, ['месяц', 'месяца', 'месяцев']) + ' назад'
        } else if (day > 0) {
          return day + ' ' + declOfNum(day, ['день', 'дня', 'дней']) + ' назад'
        } else if (hour > 0) {
          return hour + ' ' + declOfNum(hour, ['час', 'часа', 'часов']) + ' назад'
        } else if (minute > 0) {
          return minute + ' ' + declOfNum(minute, ['минута', 'минуты', 'минут']) + ' назад'
        } else {
          return sec + ' ' + declOfNum(sec, ['секунда', 'секунды', 'секунд']) + ' назад'
        }


      } else {
        return ''
      }
    }


    return {
      showTime
    }
  }
})
</script>

<style scoped>

</style>
