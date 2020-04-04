<template>
  <RightCard v-if="show" header="Погода" class="right-welcome">
    <template>
      <div align="center" class="plugin-welcome">
        <div>Температура на </div>
        <div>{{temper.time | moment('HH:mm')}} </div>
        <div style="font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 3em; padding: 15px 0; cursor: pointer;" @click="putWeather">
          {{temper.temp}}°C
        </div>
      </div>
    </template>
  </RightCard>
</template>

<script>
  import RightCard from '@/components/RightCard'
  import {getTemperNow } from '@/api/temper.js'
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
      putWeather(){
        this.$router.push('/weather/index')
      },
      fetchTemp(){
        getTemperNow().then(response => {
          this.temper = response.data.temper;
          console.log(data)
        })
      }
    },
    computed: {
      show(){
        // проверка на актуальность темепературы,выводим если занчение получено не более 2 часов назад
        if ((this.$moment(this.temper.time).format('X') - this.$moment(new Date().getTime()).format('X'))/(60*60) > 1){
          return true
        }
        return false
      },
      url() {
        return process.env.VUE_APP_BASE_API + '/../user/receipt/get-receipt-clear'
      }
    },
  }
</script>

<style scoped>
</style>

