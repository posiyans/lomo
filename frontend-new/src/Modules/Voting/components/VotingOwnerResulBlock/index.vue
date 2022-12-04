<template>
  <div class="question q-pa-md q-mb-sm">
    <div class="text-weight-bold q-pa-sm text-big-30">{{ i+1 }}.
      <span> {{ question.title }}</span>
    </div>
    <ResultAnswerItem v-for="(answer, j) in question.answers" :answer="answer" :i="j+1" :key="answer.id" class="q-mb-xs" />
    <div style="padding-left: 10px; padding-top: 15px;">
      Проголосовало {{ question.all_answer }} {{steadTitle}} ({{ question.procent }}%)
    </div>
  </div>
</template>

<script>
import { fetchVotingQuestionResultInfo } from 'src/Modules/Voting/api/voting'
import { declOfNum } from 'src/utils/helper'
import ResultAnswerItem from 'src/Modules/Voting/components/ResultAnswerItem/index.vue'

export default {
  components: {
    ResultAnswerItem
  },
  props: {
    id: {
      type: [Number, String],
      required: true
    },
    i: {
      type: Number,
      default: 1
    }
  },
  data () {
    return {
      question: {}
    }
  },
  computed: {
    steadTitle() {
      return declOfNum(this.question.all_answer, ['участок', 'участка', 'участков'])
    }
  },
  mounted () {
    this.getData()
  },
  methods: {
    getData() {
      const data = {
        id: this.id
      }
      fetchVotingQuestionResultInfo(data)
        .then(res => {
          this.question = res.data.data
        })
    }
  }
}
</script>

<style scoped>
.question{
  background: linear-gradient(315deg, rgb(54, 150, 223) 0%, rgba(175,217,249,1) 100%);
  border-radius: 10px;
}
</style>
