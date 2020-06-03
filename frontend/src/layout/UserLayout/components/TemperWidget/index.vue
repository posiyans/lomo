<template>
  <span v-if="temper" style="font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 3em; color: #1b5fab;">
      {{temper.temp}}°C
  </span>
</template>

<script>
import { getNowWeatherProHD } from '@/api/temper.js'
export default {
  name: 'TemperWidget',
  data() {
    return {
      temper: false
    }
  },
  mounted() {
    this.fetchTemp()
  },
  methods: {
    fetchTemp() {
      getNowWeatherProHD().then(response => {
        this.temper = response.data.temper
      })
    }
  },
}
</script>

<style scoped>
</style>

