<template>
  <span v-if="temper" class="temp-block pointer" @click="pushToGraf">
    {{ temper.temp }}°C
  </span>
</template>

<script>
import { getNowWeatherProHD } from '@/api/temper.js'
export default {
  data() {
    return {
      temper: false
    }
  },
  mounted() {
    this.fetchTemp()
  },
  methods: {
    pushToGraf() {
      this.$router.push('/modules/weather')
    },
    fetchTemp() {
      getNowWeatherProHD().then(response => {
        this.temper = response.data.temper
      })
    }
  }
}
</script>

<style scoped>
  .temp-block {
    font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    color: #1b5fab;
  }
</style>

