<template>
  <div>
    <div class="text-h6">Погода</div>
    <div>
      <WeatherPro />
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import WeatherPro from 'src/Modules/Weather/componets/WeatherPro/index.vue'

export default defineComponent({
  components: {
    WeatherPro
  },
  setup () {
    return {
    }
  }
})
</script>

<style scoped>

</style>
