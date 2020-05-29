<template>
  <div>
<!--    v-loading="loading"-->
<!--    element-loading-text="Загрузка..."-->
<!--    element-loading-spinner="el-icon-loading"-->
<!--    element-loading-background="rgba(0, 0, 0, 0.8)"-->
<!--  >-->
    <div
      v-loading="loading"
      element-loading-text="Загрузка..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.8)"
      id="temper"
      :style="{height:nheight,width:nwidth}"
    />
    <div
      v-loading="loading"
      element-loading-text="Загрузка..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.8)"
      id="precipitation"
      :style="{height:'500px', width: '100%'}"
    />
  </div>
</template>

<script>
import {  getNewWeatherProHD } from '@/api/temper.js'
import echarts from 'echarts'
export default {
  props: {
    type: {
      type: String,
      default: '1'
    }
  },
  data() {
    return {
      loading: true,
      id: 'chart',
      className: 'chart',
      nheight: '500px',
      nwidth: '100%',
      chart: null,
      chart1: null,
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
  beforeDestroy() {
    if (!this.chart) {
      return
    }
    this.chart.dispose()
    this.chart = null
    if (!this.chart1) {
      return
    }
    this.chart1.dispose()
    this.chart1 = null
  },
  mounted() {
    getNewWeatherProHD().then(response => {
      // let i = 0;
      for (const temp of response.data.temper) {
        // console.log(temp)
        const tt = this.$moment(temp.time) + this.$moment().tz(this.$moment.tz.guess(true)).utcOffset() * 60 * 1000
        this.data.push({name: tt, value: [tt, temp.temp]})
        this.datav.push({name: temp.time, value: [temp.time, temp.temp]})
        // this.data.push( [temp.time, temp.temp]);
        // this.data.push([i++, temp.temp]);
        // this.datav =[{name: temp.time, value: [temp.time, temp.temp]}];
      }
      for (const temp of response.data.os) {
        // console.log(temp)
        const tt = this.$moment(temp.time) + this.$moment().tz(this.$moment.tz.guess(true)).utcOffset() * 60 * 1000
        this.os.push({name: tt, value: [tt, temp.temp]})
      }
      for (const temp of response.data.osv) {
        // console.log(temp)
        const tt = this.$moment(temp.time) + this.$moment().tz(this.$moment.tz.guess(true)).utcOffset() * 60 * 1000
        this.osv.push({name: tt, value: [tt, temp.temp]})
      }
      // for (const temp of response.data.sunrise) {
      //   this.dataSun.push([{xAxis: temp.start}, {xAxis: temp.stop}]);
      // }
      // for (const temp of response.data.dusk) {
      //   this.dataSumer.push([{xAxis: temp.start}, {xAxis: temp.stop}]);
      // }
      this.loading = false
      this.initTemperChart()
      this.initPrecipitationChart()
    })
  },
  methods: {
    randomData() {
      this.now = new Date(+this.now + this.oneDay)
      this.value = this.value + Math.random() * 21 - 10
      return {
        name: this.now.toString(),
        value: [
          [this.now.getFullYear(), this.now.getMonth() + 1, this.now.getDate()].join('/'),
          Math.round(this.value)
        ]
      }
    },
    initTemperChart() {
      this.chart = echarts.init(document.getElementById('temper'))
      this.chart.setOption({
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
          formatter: function(params) {
            params = params[0]
            var date = new Date(params.name)
            return date.getHours() + ':00' + ' ' + date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear() + ' : ' + params.value[1] + '°C'
          },
          axisPointer: {
            lineStyle: {
              color: '#bbb7c2'
            }
          }
        },
        grid: {
          top: 80,
          left: '4%',
          right: '4%',
          bottom: '2%',
          containLabel: true
        },
        dataZoom: [
          {
            id: 'dataZoomX',
            type: 'slider',
            xAxisIndex: [0],
            filterMode: 'filter',
            start: 0,
            end: 47
          },
        ],
        xAxis: [{
          type: 'time',
          name: 'time',
          // boundaryGap: false,
          // minorTick: {
          //   show: true
          // },
          splitLine: {
            lineStyle: {
              type: 'dashed'
            },
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
          },

        }],
        visualMap: [{
          orient: 'horizontal',
          top: 10,
          right: 10,
          pieces: [{
            lte: -15,
            color: '#05ffb9'
          },{
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
        series: [
          {
            smooth: false, // сглаживание
            name: 'Температура',
            type: 'line',
            showSymbol: true,
            hoverAnimation: true,
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
            data: this.data
          },

          ]
      })
    },
    initPrecipitationChart() {
      this.chart1 = echarts.init(document.getElementById('precipitation'))
      this.chart1.setOption({
        backgroundColor: '#ffffff',
        title: {
          top: 20,
          text: 'Осадки',
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
          left: '5%',
          right: '5%',
          bottom: '2%',
          containLabel: true
        },
        dataZoom: [
          {
            id: 'dataZoomX',
            type: 'slider',
            xAxisIndex: [0],
            filterMode: 'filter',
            start: 0,
            end: 47
          },
        ],
        xAxis: [{
          type: 'time',
          // boundaryGap: false,
          minorTick: {
            show: true
          },
          splitLine: {
            show: true,  //показать линии осей
            interval: '0'
          },
          min: new Date() - 3 * 1000 * 3600,
          minInterval: 12*3600*1000,
          maxInterval: 12*3600*1000
          // splitNumber: 7
        }],
        yAxis: [
          {
            type: 'value',
            name: 'Кол-во осадков, мм',
            nameRotate: 0,
            nameTextStyle: {
              verticalAlign: 'bottom',
              align: 'left',
              fontWeight: 'bold',
            },
            boundaryGap: false,
            min: 0,
            max: 5,
            splitLine: {
              show: true
            }
          },
          {
            type: 'value',
            name: 'Вероятность осадков, %',
            min: 0,
            max: 100,
            boundaryGap: false,
            splitLine: {
              show: true
            }
          }
          ],
        series: [
          {
            type: 'bar',
            name: 'Кол-во осадков',
            showSymbol: false,
            hoverAnimation: true,
            data: this.os
          },
          {
            type: 'line',
            name: 'Вероятность осадков',
            yAxisIndex: 1,
            showSymbol: false,
            hoverAnimation: true,
            data: this.osv
          }
        ]
      })
    }
  }
}
</script>

<style scoped>

</style>
