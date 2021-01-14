<template>
  <div>
    <div class="mb2">
      <el-select
        v-model="question_id"
        placeholder="Выбрать вопрос"
        style="width: 300px;"
        @change="getResult"
      >
        <el-option
          v-for="item in question"
          :key="item.id"
          :label="item.text"
          :value="item.id"
        />
      </el-select>

    </div>
    <div class="flex">
      <div class="dark-green b-ns cell"> За </div>
      <div class="red b-ns cell">Против </div>
    </div>
    <div />
    <div class="flex">
      <div class="b-ns cell" style="color: #ffa200">Воздержался </div>
      <div class="b-ns cell">Не голосовал</div>
    </div>
    <div />
    <table :key="tablkey">
      <tr v-for="i in line" :key="'c' + i">
        <td v-for="j in column" :key="'l' + i + '-'+ j">
          <!--          <span v-if="i + '-' + j in rezult" :class="{ 'dark-red': !rezult[i + '-' + j].answer, 'dark-green': rezult[i + '-' + j].answer }">-->
          <!--            {{ rezult[i + '-' + j].number }}-->
          <!--          </span>-->
          <span v-if="i + '-' + j in rezult" :style="color[rezult[i + '-' + j].answer]">
            {{ rezult[i + '-' + j].number }}
          </span>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { getSteadsList } from '@/api/user/stead'
import { getQuestionResult } from '@/api/user/voting'
export default {
  props: {
    steads: {
      type: Object,
      default: () => ({})
    },
    question: {
      type: Object,
      default: () => {}
    }
  },
  data() {
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
    mobile() {
      return this.$store.state.app.device === 'mobile'
    },
    column() {
      if (this.mobile) {
        return 5
      }
      return 15
    },
    line() {
      return Math.ceil(this.list.length / this.column)
    },
    countAnwers() {
      return Object.keys(this.steads).length
    },
    countNotAnswer() {
      return this.list.length - this.countAnwers
    }
  },
  mounted() {
    this.getSteads()
  },
  methods: {
    getColor() {
      this.question.forEach(q => {
        q.answers.forEach(a => {
          this.color[a.id] = 'color: #ffa200'
          if (a.text.toLowerCase() == 'за') {
            this.color[a.id] = 'color: #00ff00'
          }
          if (a.text.toLowerCase() == 'против') {
            this.color[a.id] = 'color: #ff0000'
          }
        })
      })
      this.tablkey++
    },
    getResult() {
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
    syncList() {
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
    syncQuestList() {
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
    getSteads() {
      const data = {
        limit: 1000,
        page: 1
      }
      getSteadsList(data)
        .then(response => {
          this.list = response.data
          this.syncList()
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
  .cell {
    border: 1px solid #949494;
    text-align: center;
    width: 100px;
    display: inline-block;
    margin-bottom: 5px;
  }
</style>
