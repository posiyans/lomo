<template>
  <div :key="load">
    <table>
      <tr>
        <th></th>
        <th>Температура</th>
        <th>Осадки</th>
        <th>Давление</th>
      </tr>
      <tr v-for="item in list" :key="item.name" class="table__hover" :class="{ 'bg-grey-2': !item.now && item.bg, 'bg-teal-2': item.now }">
        <td v-if="item.type === 'day'" class="q-pa-md" colspan="4">
          <ShowTime :time="item.name" format="DD MMMM YYYY" class="text-weight-bold" />
        </td>
        <td v-if="item.type === 'hour'" class="q-px-md">
          <ShowTime :time="item.name" format="HH:mm" class="text-weight-bold" />
        </td>
        <td v-if="item.type === 'hour'" class="text-center q-px-md ">
          <div class="q-pl-md text-teal text-weight-bold">
            {{ item.raw.tt }}°C
            <q-tooltip>ощущается как {{ item.raw.td }} °C</q-tooltip>
          </div>
          <div>
            <q-icon name="wb_sunny" color="yellow" />
            {{ item.raw.sun }} мин
          </div>
        </td>
        <td v-if="item.type === 'hour'" class="q-pl-md">
          <div v-if="item.raw.rrr > 0">
            <div>
              <q-icon name="opacity" color="primary" />
              {{ item.raw.rrr }} мм
            </div>
            <div>
              <q-icon name="opacity" color="primary" />
              {{ item.raw.prrr }}%
            </div>
          </div>
          <div v-else>нет</div>
        </td>
        <td v-if="item.type === 'hour'" class="q-px-md">
          {{ Math.round(item.raw.ppp * 0.750062) }} мм рт. ст.
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { computed, defineComponent, onMounted, reactive, ref } from 'vue'
import { getWeather } from 'src/Modules/Weather/api/temper.js'
import { date } from 'quasar'
import ShowTime from 'components/ShowTime/index.vue'

export default defineComponent({
  components: {
    ShowTime
  },
  setup() {
    const load = ref(false)
    const data = reactive({
      table: []
    })
    const list = computed(() => {
      const res = []
      let bg = false
      const dateTarget = new Date()
      data.table.forEach(item => {
        res.push({
          type: 'day',
          name: item.name
        })
        bg = true
        item.value.forEach(i => {
          res.push({
            type: 'hour',
            name: i.name,
            raw: i.raw,
            bg,
            now: date.isBetweenDates(
              dateTarget,
              date.extractDate(i.raw.time, 'YYYY-MM-DDTHH:mm:ssZ'),
              date.addToDate(date.extractDate(i.raw.time, 'YYYY-MM-DDTHH:mm:ssZ'), { second: i.raw.interval })
            )
          })
          bg = !bg
        })
      })
      return res
    })
    const getData = () => {
      getWeather({ all: true })
        .then(response => {
          const tmp = {}
          response.data.temper.forEach(item => {
            const g = date.formatDate(date.extractDate(item.time, 'YYYY-MM-DDTHH:mm:ssZ'), 'YYYY-MM-DD')
            const t = date.formatDate(date.extractDate(item.time, 'YYYY-MM-DDTHH:mm:ssZ'), 'YYYY-MM-DDTHH:mm:ss')
            if (tmp[g]) {
              tmp[g].value.push({ name: t, raw: item })
            } else {
              tmp[g] = {
                name: g,
                value: [
                  { name: t, raw: item }
                ]
              }
            }
          })
          data.table = Object.values(tmp)
          load.value = true
        })
    }

    onMounted(() => {
      getData()
    })

    return {
      list,
      load
    }
  }
})
</script>

<style scoped lang="scss">
.table__hover {
  &:hover {
    background-color: #eaeaea !important;
  }
}
</style>
