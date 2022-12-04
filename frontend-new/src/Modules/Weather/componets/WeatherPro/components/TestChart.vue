<template>
  <div :key="data.key">
    <v-chart class="chart" :option="option" />
  </div>
</template>

<script>
import { use } from 'echarts/core'
import { CanvasRenderer } from 'echarts/renderers'
import { LineChart, BarChart } from 'echarts/charts'
import {
  TitleComponent,
  TooltipComponent,
  LegendComponent,
  VisualMapComponent,
  GridComponent
} from 'echarts/components'
import VChart from 'vue-echarts'
import { ref, defineComponent, onMounted, reactive } from 'vue'
import { getNewWeatherProHD } from 'src/Modules/Weather/api/temper.js'

use([
  CanvasRenderer,
  LineChart,
  BarChart,
  TitleComponent,
  TooltipComponent,
  LegendComponent,
  GridComponent,
  VisualMapComponent
])

import { date } from 'quasar'

export default defineComponent({
  components: {
    VChart
  },
  setup () {
    const data = reactive({
      data: [],
      os: {},
      osv: {},
      key: 1
    })
    const getData = () => {
      getNewWeatherProHD().then(response => {
        console.log(response.data)
        response.data.temper.forEach(item => {
          const t = date.extractDate(item.time, 'YYYY-MM-DDTHH:mm:ssZ')
          data.data.push({ name: t, value: [t, item.temp] })
        })
        response.data.os.forEach(item => {
          const t = date.extractDate(item.time, 'YYYY-MM-DDTHH:mm:ssZ')
          data.os[t] = { value: item.temp }
        })
        response.data.osv.forEach(item => {
          const t = date.extractDate(item.time, 'YYYY-MM-DDTHH:mm:ssZ')
          data.osv[t] = { value: item.temp }
        })
        data.key++
      })
    }

    onMounted(() => {
      getData()
    })

    const option = ref({
      backgroundColor: '#ffffff',
      title: {
        top: 20,
        text: 'Температура',
        textStyle: {
          fontWeight: 'normal',
          fontSize: 16,
          color: '#030303'
        },
        left: '1%'
      },
      tooltip: {
        trigger: 'axis',
        formatter: (params) => {
          return params[0].axisValueLabel + '<br/>' + 'Температура : ' + params[0].value[1] + '°C' + '<br/>Кол-во осадков: ' + data.os[params[0].value[0]].value + 'мм' + '<br/>Вероятность осадков: ' + data.osv[params[0].value[0]].value + '%'
        },
        axisPointer: {
          lineStyle: {
            color: '#bbb7c2'
          }
        }
      },
      visualMap: [{
        orient: 'horizontal',
        top: 10,
        right: 10,
        pieces: [{
          lte: -15,
          color: '#05ffb9'
        }, {
          gt: -15,
          lte: 0,
          color: '#0200ed'
        }, {
          gt: 0,
          lte: 15,
          color: '#cc0033'
        }, {
          gt: 15,
          lte: 25,
          color: '#4e7e00'
        }, {
          gt: 25,
          color: '#007e2e'
        }],
        outOfRange: {
          color: '#1d7c5f'
        }
      }],
      grid: {
        top: 80,
        left: '4%',
        right: '4%',
        bottom: '2%',
        containLabel: true
      },
      xAxis: [{
        type: 'time',
        name: 'time',
        boundaryGap: false,
        minorTick: {
          show: true
        },
        splitLine: {
          lineStyle: {
            type: 'dashed'
          }
          // splitNumber: 20,
          // show: true,  //показать линии осей
          // interval: '0'
        },
        axisLine: {
          show: false
        },

        // splitNumber: 7,
        min: new Date() - 3 * 1000 * 3600,
        minInterval: 12 * 3600 * 1000,
        maxInterval: 12 * 3600 * 1000
      }],
      yAxis: [{
        type: 'value',
        name: '°C',
        boundaryGap: false,
        splitLine: {
          show: true
        }

      }],
      series: [
        {
          smooth: false, // сглаживание
          name: 'Температура',
          type: 'line',
          showSymbol: true,
          // markArea: {
          //   silent: true,
          //   data: this.dataSun
          // },
          // emphasis: {
          //   label: {
          //     show: true,
          //     position: 'left',
          //     color: 'blue',
          //     fontSize: 16
          //   }
          // },
          data: data.data
        }
      ]
    })

    return {
      option,
      data
    }
  }
})
</script>

<style scoped>
.chart {
  height: 100vh;
}
</style>
