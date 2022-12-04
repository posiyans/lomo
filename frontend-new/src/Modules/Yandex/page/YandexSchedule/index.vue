<template>
  <div>
    <div class="text-h6">Расписание электричек</div>
    <q-separator />
    <div class="q-pt-md">
      <YandexSchedule />
    </div>
  </div>
</template>

<script>
import YandexSchedule from 'src/Modules/Yandex/components/YandexSchedule/index.vue'

export default {
  components: {
    YandexSchedule
  }

}
</script>

<style scoped>
</style>
