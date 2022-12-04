<template>
  <div>
    <q-card
      v-for="(question, i) in voting.questions"
      :key="question.id"
      class="q-mb-md"
    >
      <q-card-section class="bg-grey-3">
        <div class="text-weight-bold q-pa-sm">
          {{ ++i }}. {{ question.text }}
        </div>
      </q-card-section>
      <q-card-section>
        <div
          v-for="(answer, j) in question.answers"
          :key="answer.id"
          class="text-uppercase q-pa-sm"
        >
          <div>
            {{ ++j }}. {{ answer.text }}
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  props: {
    voting: {
      type: Object,
      default: () => ({})
    }
  }
}
</script>

<style scoped>
</style>
