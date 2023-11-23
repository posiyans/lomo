<template>
  <span v-if="temper" class="temp-block cursor-pointer" @click="pushToGraf">
    {{ temper.tt }}°C
  </span>
</template>

<script>
import { getWeather } from 'src/Modules/Weather/api/temper'

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
      this.$router.push('/weather/show')
    },
    fetchTemp() {
      getWeather()
        .then(response => {
          this.temper = response.data.data
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
