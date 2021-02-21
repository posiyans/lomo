<template>
  <div :id="id" :class="className" :style="{height:height,width:width}"/>
</template>

<script>
import echarts from 'echarts'
import { getTemper } from '@/api/temper.js'

export default {
  data() {
    return {
      id: 'chart',
      className: 'chart',
      height: '500px',
      width: '100%',
      chart: null,
      data: [],
      datav: [],
      dataSun: [],
      dataSumer: []
    }
  },
  mounted() {
    getTemper().then(response => {
      const data = response.data;
      for (const temp of response.data.temper) {
        this.data.push({ name: temp.time, value: [temp.time, temp.temp] });
        this.datav =[{ name: temp.time, value: [temp.time, temp.temp] }];


      }
      for (const temp of response.data.sunrise) {
        this.dataSun.push([{xAxis: temp.start}, {xAxis: temp.stop}]);
      }
      for (const temp of response.data.dusk) {
        this.dataSumer.push([{xAxis: temp.start}, {xAxis: temp.stop}]);
      }
      this.initChart()
    })
  },
  beforeDestroy() {
    if (!this.chart) {
      return
    }
    this.chart.dispose()
    this.chart = null
  },
  methods: {
    initChart() {
      this.chart = echarts.init(document.getElementById(this.id))
      this.chart.setOption({
        backgroundColor: '#ffffff',
        title: {
          top: 20,
          text: 'ЧАЩА, температура за прошедшую неделю',
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
            filterMode: 'filter'
          },
        ],
        xAxis: [{
          type: 'time',
          boundaryGap: false,
          splitLine: {
            show: true
          },
          splitNumber: 7
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
            smooth: true,
            name: 'Температура',
            type: 'line',
            showSymbol: false,
            hoverAnimation: true,
            markArea: {
              silent: true,
              data: this.dataSun
            },
            data: this.data
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
