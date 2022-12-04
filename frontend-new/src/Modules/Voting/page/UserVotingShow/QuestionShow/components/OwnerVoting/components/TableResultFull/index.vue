<template>
  <div>
    <SelectQuestionForVoting
        v-if="votingId"
        v-model="question_id"
        outlined
        :voting-id="votingId"
        @update:modelValue="getResult"
    />
    <div class="row q-py-sm">
      <ShowAnswersForQuestion v-if="question_id" :question-id="question_id" add-no-answer>
        <template v-slot:default="{ answer }">
          <AnswerBlock :label="answer.text" :answer="answer.id" class="voting-result-cell"/>
        </template>
      </ShowAnswersForQuestion>
    </div>
    <div v-if="question_id" class="row items-center">
      <AnswerBlock  v-for="i in list" :key="i.id" :label="i.number" :answer="AnswerList[i.id]" class="voting-result-cell"/>
    </div>
  </div>
</template>

<script>
import { getSteadsList } from 'src/Modules/Stead/api/stead.js'
import { getQuestionResult } from 'src/Modules/Voting/api/voting.js'
import SelectQuestionForVoting from 'src/Modules/Voting/components/SelectQuestionForVoting/index.vue'
import ShowAnswersForQuestion from 'src/Modules/Voting/components/ShowAnswersForQuestion/index.vue'
import AnswerBlock from './components/AnswerBlock/index.vue'
export default {
  components: {
    SelectQuestionForVoting,
    ShowAnswersForQuestion,
    AnswerBlock
  },
  props: {
    steads: {
      type: Object,
      default: () => ({})
    },
    votingId: {
      type: [String, Number],
      required: true
    },
    question: {
      type: Object,
      default: () => {}
    }
  },
  data () {
    return {
      question_id: '',
      list: [],
      rezult: {},
      AnswerList: [],
      answer: [],
      color: {},
      tablkey: 1
    }
  },
  computed: {
    mobile () {
      return this.$q.platform.is.mobile
    },
    column () {
      if (this.mobile) {
        return 5
      }
      return 15
    },
    line () {
      return Math.ceil(this.list.length / this.column)
    },
    countAnwers () {
      return Object.keys(this.steads).length
    },
    countNotAnswer () {
      return this.list.length - this.countAnwers
    }
  },
  mounted () {
    this.getSteads()
  },
  methods: {
    getColor () {
      this.question.forEach(q => {
        q.answers.forEach(a => {
          this.color[a.id] = 'color: #ffa200'
          if (a.text.toLowerCase() === 'за') {
            this.color[a.id] = 'color: #00ff00'
          }
          if (a.text.toLowerCase() === 'против') {
            this.color[a.id] = 'color: #ff0000'
          }
        })
      })
      this.tablkey++
    },
    getResult () {
      getQuestionResult({ question_id: this.question_id })
        .then(response => {
          if (response.data.status) {
            this.AnswerList = response.data.data
            this.syncQuestList()
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    syncList () {
      let i = 1
      let j = 1
      this.list.forEach(item => {
        if (item.id in this.steads) {
          this.rezult[i + '-' + j] = { number: item.number, answer: true }
        } else {
          this.rezult[i + '-' + j] = { number: item.number, answer: false }
        }
        j += 1
        if (j > this.column) {
          j = 1
          i += 1
        }
      })
    },
    syncQuestList () {
      let i = 1
      let j = 1
      this.list.forEach(item => {
        if (this.AnswerList[item.id]) {
          this.rezult[i + '-' + j] = { number: item.number, answer: this.AnswerList[item.id] }
        } else {
          this.rezult[i + '-' + j] = { number: item.number, answer: false }
        }
        j += 1
        if (j > this.column) {
          j = 1
          i += 1
        }
      })
      this.getColor()
    },
    getSteads () {
      getSteadsList()
        .then(response => {
          this.list = response.data.data
          // this.syncList()
        })
    }
  }

}
</script>

<style scoped>
  table {
    border-collapse: collapse;
    width: 100%;
  }
  table tr td {
    border: 1px solid #949494;
    text-align: center;
  }
</style>
