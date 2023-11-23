<template>
  <div>
    <div class="page-title">
      Расписание электричек
    </div>
    <q-card>
      <q-card-section>
        <YandexSchedule />
      </q-card-section>
    </q-card>
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
