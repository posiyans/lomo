<template>
  <div>
    <div>
      <div v-if="value.status == 'new'">
        <div class="text-h6 text-negative">
          Голосование еще не начато
        </div>
        <div class="text-weight-bold q-py-sm"> Вопросы голосования</div>
        <ShowBulletin :voting="value" />
      </div>
      <div v-if="value.status == 'execution'">
        <div v-if="false" class="=q-pa-md">
          <q-btn no-caps no-wrap color="negative" label="Проголосовать" @click="sendFile" />
        </div>
        <div class="text-weight-bold q-py-sm"> Вопросы голосования</div>
        <ShowBulletin :voting="value" />
        <div class="text-red-10 q-pa-sm row q-col-gutter-sm">
          <div>
            Результаты голосования будут показаны по окончанию голосования
          </div>
          <ShowTime :time="value.date_stop" format="DD-MM-YYYY" class="text-weight-bold text-primary" />
        </div>
        <TableResult :steads="value.voted" />
      </div>
      <div v-if="value.status == 'done'">
        <el-tabs type="border-card">
          <el-tab-pane label="Результат">
            <div v-for="(question, i) in value.questions" :key="question.id">
              <ResultBlock :id="question.id" :i="i" />
            </div>
          </el-tab-pane>
          <el-tab-pane label="Участки">
            <TableResult :steads="value.voted" />
          </el-tab-pane>
          <el-tab-pane label="Подробнее">
            <TableResultFull :steads="value.voted" :voting-id="value.id" :question="value.questions" />
          </el-tab-pane>
        </el-tabs>
      </div>
      <div v-if="value.status == 'cancel'">
        <div class="text-h4 text-center text-red-10 text-weight-bold">
          Голосование отменено
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import ShowBulletin from './components/ShowBulletin'
import TableResult from './components/TableResult'
import TableResultFull from './components/TableResultFull'
import ResultBlock from 'src/Modules/Voting/components/VotingOwnerResulBlock/index.vue'
import ShowTime from 'src/components/ShowTime/index.vue'
export default {
  components: {
    // ResultBlock,
    TableResult,
    ShowBulletin,
    ResultBlock,
    TableResultFull,
    ShowTime

  },

  props: {
    value: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    link () {
      return '/voting/send-file/' + this.value.id
    }
  },
  methods: {
    sendFile () {
      this.$router.push(this.link)
    }
  }
}
</script>

<style scoped>

</style>
