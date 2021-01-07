<template>
  <div class="app-container">
    <div>
      <div v-if="value.status == 'new'">
        <h3>
          Голосование еще не начато
        </h3>
        <div class="b-ns"> Вопросы голосования</div>
        <ShowBulletin :voting="value" />
      </div>
      <div v-if="value.status == 'execution'">
        <div class="pb3">
          <el-link type="danger" @click="sendFile">Проголосовать!!!</el-link>
        </div>
        <div class="b-ns"> Вопросы голосования</div>
        <ShowBulletin :voting="value" />
        <div class="dark-red mb2">
          Результаты голосования будут показаны по окончанию голосования ({{ value.date_stop | moment('DD-MM-YYYY') }})
        </div>
        <TableResult :steads="value.voted" />
      </div>
      <div v-if="value.status == 'done'">
        <el-tabs type="border-card">
          <el-tab-pane label="Результат">
            <div v-for="(question, i) in value.questions" :key="question.id">
              <ResultBlock :question="question" :i="i" :steat-count="value.steadsCount" />
            </div>
          </el-tab-pane>
          <el-tab-pane label="Участки">
            <TableResult :steads="value.voted" />
          </el-tab-pane>
        </el-tabs>
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
import ResultBlock from '@/components/VotingOwnerResulBlock'

export default {
  components: {
    // ResultBlock,
    TableResult,
    ShowBulletin,
    ResultBlock

  },

  props: {
    value: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    link() {
      return '/voting/send-file/' + this.value.id
    }
  },
  methods: {
    sendFile() {
      this.$router.push(this.link)
    }
  }
}
</script>

<style scoped>

</style>
