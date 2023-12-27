<template>
  <div :key="data.key">
    <v-chart
      class="chart"
      autoresize
      :loading="loading"
      :loadingOptions="loadingOptions"
      :option="option"
    />
  </div>
</template>

<script>
import { use } from 'echarts/core'
import { CanvasRenderer } from 'echarts/renderers'
import { BarChart, LineChart } from 'echarts/charts'
import { GridComponent, LegendComponent, MarkAreaComponent, TitleComponent, TooltipComponent, VisualMapComponent } from 'echarts/components'
import VChart from 'vue-echarts'
import { defineComponent, onMounted, reactive, ref } from 'vue'
import { getWeather } from 'src/Modules/Weather/api/temper.js'
import { date } from 'quasar'

use([
  CanvasRenderer,
  LineChart,
  BarChart,
  TitleComponent,
  TooltipComponent,
  LegendComponent,
  GridComponent,
  MarkAreaComponent,
  VisualMapComponent
])

export default defineComponent({
  components: {
    VChart
  },
  setup() {
    const loading = ref(false)
    const loadingOptions = {
      text: 'Loading…',
      color: '#4ea397',
      maskColor: 'rgba(255, 255, 255, 0.4)'
    }
    const rawData = ref([])
    const data = reactive({
      data: [],
      key: 1
    })
    const getData = () => {
      loading.value = true
      getWeather({ all: true })
        .then(response => {
          const dt = []
          const tmp = []
          const tmpOs = []
          const days = {}
          response.data.temper.forEach(item => {
            rawData.value.push(item)
            dt.push(date.formatDate(date.extractDate(item.time, 'YYYY-MM-DDTHH:mm:ssZ'), 'YYYY-MM-DD HH:mm'))
            tmp.push({ value: item.tt, raw: item })
            const col = item.prrr / 100
            tmpOs.push({
              value: item.rrr,
              raw: item,
              itemStyle: {
                color: 'rgba(0, 0, 100,' + col + ')'
              }
            })
            days[date.formatDate(date.extractDate(item.time, 'YYYY-MM-DDTHH:mm:ssZ'), 'YYYY-MM-DD')] = 1
          })
          option.value.xAxis.data = dt.map(function (str) {
            return str.replace(' ', '\n')
          })
          option.value.series[0].data = tmp
          option.value.series[1].data = tmpOs
          const dayTmp = []
          let include = true

          Object.keys(days).forEach(item => {
            if (include) {
              let l = date.formatDate(date.addToDate(date.extractDate(item, 'YYYY-MM-DD'), { day: 1 }), 'YYYY-MM-DD') + '\n00:00'
              if (!option.value.xAxis.data.includes(l)) {
                if (option.value.xAxis.data[option.value.xAxis.data.length - 1] !== item + '\n00:00') {
                  l = option.value.xAxis.data[option.value.xAxis.data.length - 1]
                } else {
                  l = false
                }
              }
              if (l) {
                dayTmp.push(
                  [
                    {
                      name: date.formatDate(date.extractDate(item, 'YYYY-MM-DD'), 'dddd'),
                      xAxis: dayTmp.length === 0 ? option.value.xAxis.data[0] : item + '\n00:00'
                    },
                    {
                      xAxis: l
                    }
                  ]
                )
              }
            }
            include = !include
          })
          option.value.series[0].markArea.data = dayTmp
        })
        .finally(() => {
          loading.value = false
        })
    }

    onMounted(() => {
      getData()
    })

    const option = ref({
      title: {
        text: 'Температура',
        subtext: 'Прогноз на неделю',
        position: 'center'
      },
      xAxis: {
        type: 'category',
        boundaryGap: true,
        data: []
      },
      yAxis: [
        {
          axisLabel: {
            formatter: '{value} °C'
          },
          axisPointer: {
            snap: true
          },
          type: 'value',
          name: '°C',
          boundaryGap: false,
          splitLine: {
            show: true
          }
        },
        {
          axisLabel: {
            formatter: '{value} мм'
          },
          position: 'right',
          axisPointer: {
            snap: true
          },
          max: function (value) {
            if (value.max > 1) {
              return value.max
            }
            return 1
          },
          type: 'value',
          name: 'Осадки, мм',
          boundaryGap: false,
          splitLine: {
            show: true
          }
        }
      ],
      tooltip: {
        trigger: 'axis',
        formatter: (params) => {
          return params[0].axisValueLabel + '<br/>' + 'Температура: ' + params[0].data.value + '°C' + '<br/>' + 'Ощущается как: ' + params[0].data.raw.td + '°C' + '<br/>Кол-во осадков: ' + params[0].data.raw.rrr + 'мм' + '<br/>Вероятность осадков: ' + params[0].data.raw.prrr + '%'
        },
        axisPointer: {
          type: 'cross',
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
          gt: -5,
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
      series: [
        {
          name: 'Температура',
          type: 'line',
          smooth: true,
          // prettier-ignore
          data: [],
          markArea:
            {
              itemStyle: {
                color: 'rgba(203,203,203,0.2)'
              },
              emphasis: {
                itemStyle: {
                  color: 'rgba(203,210,203,0.4)'
                }
              },
              animation: true,
              data: []
            }

        },
        {
          name: 'Кол-во осадков',
          type: 'bar',
          // colorBy: 'data',
          yAxisIndex: 1,
          smooth: true,
          itemStyle: {
            show: true,
            color: '#00ff00'
          },
          label: {
            show: true,
            rotate: 90,
            formatter: function (value) {
              if (rawData.value[value.dataIndex].prrr > 20) {
                return rawData.value[value.dataIndex].prrr + ' %'
              }
              return ''
            }
          },
          // prettier-ignore
          data: []

        }
      ]
    })

    return {
      option,
      loading,
      loadingOptions,
      data
    }
  }
})
</script>

<style scoped lang="scss">
.chart {
  height: 80vh;
}
</style>
