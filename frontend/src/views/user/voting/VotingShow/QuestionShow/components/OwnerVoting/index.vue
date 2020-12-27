<template>
  <div class="app-container">
    <div>
      <div class="b-ns"> Вопросы голосования</div>
      <ShowBulletin :voting="value" />
      <div v-if="value.status == 'new'">
        <h3>
          Голосование еще не начето
        </h3>
      </div>
      <div v-if="value.status == 'execution'">
        <div class="dark-red mb2">
          Результаты голосования будут показаны по окончанию голосования ({{ value.date_stop | moment('DD-MM-YYYY') }})
        </div>
        <TableResult :steads="value.voted" />
      </div>
      <div v-if="value.status == 'done'">
        <!--        <div v-for="(question, i) in value.questions" :key="question.id">-->
        <!--          <ResultBlock :question="question" :i="i" :steat-count="value.steadsCount" />-->
        <!--        </div>-->
      </div>
      <div v-if="value.status == 'cancel'">
        <h3>
          Голосование отменено
        </h3>
      </div>
    </div>
  </div>
</template>

<script>

import ShowBulletin from './components/ShowBulletin'
// import ResultBlock from '@/components/VotingOwnerResulBlock'
import TableResult from './components/TableResult'

export default {
  components: {
    // ResultBlock,
    TableResult,
    ShowBulletin
  },

  props: {
    value: {
      type: Object,
      default: () => ({})
    }
  }
}
</script>

<style scoped>

</style>
