<template>
  <RightCard header="Погода" class="right-welcome">
    <template>
      <div align="center" class="plugin-welcome">
        <div>Температура на </div>
        <div>{{ temper.time | moment('HH:mm') }} </div>
        <div style="font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 1em; padding: 15px 0; cursor: pointer;" @click="putWeather">
          {{ temper.temp }}°C
        </div>
      </div>
    </template>
  </RightCard>
</template>

<script>
import RightCard from '@/components/RightCard'
import { getNowWeatherProHD } from '@/api/temper.js'
export default {
  name: 'RecaiptForm',
  components: {
    RightCard
  },
  data() {
    return {
      temper: false
    }
  },
  mounted() {
    this.fetchTemp()
  },
  methods: {
    putWeather() {
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
</style>

