<template>
  <div>
    <div><b>Наименование:</b> <span class="props-value">{{ gardening.name }}</span></div>
    <div><b>ИНН:</b> <span class="props-value">{{ gardening.PayeeINN }}</span></div>
    <div><b>Счет:</b> <span class="props-value">{{ gardening.PersonalAcc }}</span></div>
    <div><b>Банк:</b> <span class="props-value">{{ gardening.BankName }}</span></div>
    <div><b>БИК:</b> <span class="props-value">{{ gardening.BIC }}</span></div>
    <div><b>Корсчет:</b> <span class="props-value">{{ gardening.CorrespAcc }}</span></div>
  </div>
</template>

<script>
import { defineComponent, ref, onBeforeMount } from 'vue'
import { getGardeninngInfo } from 'src/Modules/Gardening/api/gardening'

export default defineComponent({
  setup () {
    const gardening = ref({})
    const getData = () => {
      getGardeninngInfo()
        .then(res => {
          gardening.value = res.data
        })
    }
    onBeforeMount(() => {
      getData()
    })
    return {
      gardening
    }
  }
})
</script>

<style scoped>

</style>
