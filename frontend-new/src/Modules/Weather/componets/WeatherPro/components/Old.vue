<template>
  <div>
    <div
      id="chartOldm"
      v-loading="loading"
      element-loading-text="Загрузка..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.8)"
      :style="{height:'500px', width: '100%'}"
    />
  </div>
</template>

<script>
import { getTemper } from 'src/Modules/Weather/api/temper.js'
import echarts from 'echarts'

export default {
  props: {
    type: {
      type: String,
      default: '1'
    }
  },
  data () {
    return {
      loading: true,
      id: 'chartOldm',
      className: 'chartold',
      height: '500px',
      width: '100%',
      chartold: null,
      data: [],
      os: [],
      osv: [],
      datav: [],
      dataSun: [],
      dataSumer: [],
      now: +new Date(1997, 9, 3),
      oneDay: 24 * 3600 * 1000,
      value: Math.random() * 1000
    }
  },
  computed: {
  },
  // beforeDestroy() {
  //   if (!this.chartold) {
  //     return
  //   }
  //   this.chartold.dispose()
  //   this.chartold = null
  // },
  mounted () {
    this.getDatay()
  },
  methods: {
    getDatay () {
      getTemper().then(response => {
      // gettestTemper().then(response => {
        for (const temp of response.data.temper) {
          const tt = this.$moment(temp.time).format()

          this.data.push({ name: temp.time, value: [tt, temp.temp] })
          // this.data.push({name: tt, value: [tt, temp.temp]})
          this.datav = [{ name: temp.time, value: [tt, temp.temp] }]
        }
        for (const temp of response.data.sunrise) {
          this.dataSun.push([{ xAxis: temp.start }, { xAxis: temp.stop }])
        }
        for (const temp of response.data.dusk) {
          this.dataSumer.push([{ xAxis: temp.start }, { xAxis: temp.stop }])
        }
        this.loading = false
        this.initChart()
      })
    },
    initChart () {
      this.chartold = echarts.init(document.getElementById('chartOldm'))
      this.chartold.setOption({
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
          axisPointer: {
            lineStyle: {
              color: '#bbb7c2'
            }
          }
        },
        grid: {
          top: 80,
          left: '2%',
          right: '2%',
          bottom: '2%',
          containLabel: true
        },
        dataZoom: [
          {
            id: 'dataZoomX',
            type: 'slider',
            xAxisIndex: [0],
            filterMode: 'filter',
            start: 65,
            end: 100
          }
        ],
        xAxis: [{
          type: 'time'
          // boundaryGap: false,
          // minorTick: {
          //   show: true
          // },
          // splitLine: {
          //   show: true,  //показать линии осей
          //   // interval: '0'
          // },

          // splitNumber: 7
        }],
        yAxis: [{
          type: 'value',
          name: '°C',
          boundaryGap: false,
          splitLine: {
            show: true
          }
        }],
        visualMap: [{
          orient: 'horizontal',
          top: 10,
          right: 10,
          pieces: [{
            lte: -25,
            color: '#05ffb9'
          }, {
            gt: -25,
            lte: -15,
            color: '#00efff'
          }, {
            gt: -15,
            lte: -5,
            color: '#0482ff'
          }, {
            gt: -5,
            lte: 0,
            color: '#0200ed'
          }, {
            gt: 0,
            lte: 5,
            color: '#cc0033'
          }, {
            gt: 5,
            lte: 15,
            color: '#995102'
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
            smooth: false, // сглаживание
            name: 'Температура',
            type: 'line',
            markArea: {
              silent: true,
              data: this.dataSun
            },
            showSymbol: false,
            // hoverAnimation: true,
            // markArea: {
            //   silent: true,
            // },
            data: this.data
            // data: [[0,1],[1,5][3,7]]
          },
          {
            smooth: false,
            name: 'Температура',
            type: 'line',
            showSymbol: false,
            hoverAnimation: true,
            markArea: {
              silent: true,
              data: this.dataSumer
            },
            data: this.datav
          }
        ]
      })
    }
  }
}
</script>

<style scoped>

</style>
