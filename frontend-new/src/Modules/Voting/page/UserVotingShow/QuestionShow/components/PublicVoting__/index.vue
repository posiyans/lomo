<template>
  <div>
    Открытое голосование:
    <el-tag :type="value.status | statusColorFilter">
      {{ value.status | statusFilter }}
    </el-tag>
    <div v-for="(question, i) in value.questions" class="question">
      <div style="padding-bottom: 5px; color: black; font-weight: 600;">{{ i+1 }}.
        <span> {{ question.text }}</span>
      </div>
      <div v-for="(answer, j) in question.answers">
        <div class="answer" :class="question.myAnswers | answerFilter(guest)">
          <div class="an" :style="answer | resultBackgroundFilter(question)" />
          <div class="an-text" @click="vote(answer,question)">
            {{ j+1 }}.
            <span>
              {{ ansverTextFilter(answer.text) }} - {{ answer.userAnswersCount }}
              <span v-if="answer.isMyAnswer" style="color: #32ff32; font-weight: 600;">
                <i class="el-icon-check" /> {{ answer | resultFilter(question) }}%
              </span>
              <span v-else style="color: #ffff0a;">
                {{ answer | resultFilter(question) }}%
              </span>
            </span>
          </div>
        </div>
      </div>
      <div style="padding-left: 10px; padding-top: 15px;">

        <span v-if="guest" style="color: darkred; padding-right: 50px;">
          Для голосования авторизуйтесь
        </span>
        Проголосовало {{ question.answersCount }} человек
      </div>
    </div>
    <el-dialog
      title="Голосуем?"
      v-model:visible="voidVisible"
      :width="device =='mobile' ? '95%' : '60%'"
    >
      <span><b>Подтведите ответ: </b>{{ answer.text }}</span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="voidVisible = false">Отмена</el-button>
        <el-button type="primary" @click="confirmAnswer(answer.id)">Проголосовать</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { userAddAnswerToVoting } from '@/api/user/voting'
import waves from '@/directive/waves' // waves directive
import { mapState } from 'vuex'

const selectStatusOptions = [
  { key: 'new', display_name: 'Новое' },
  { key: 'execution', display_name: 'Идет' },
  { key: 'done', display_name: 'Законченно' },
  { key: 'cancel', display_name: 'Отмененное' }
]

const Status = selectStatusOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'UserVotingResult',
  components: {
  },
  directives: { waves },
  filters: {
    answerFilter (val, guest) {
      if (guest) {
        return ''
      }
      if (!val) {
        return 'cursor_pointer'
      }
    },
    statusColorFilter (status) {
      const color = {
        new: 'info',
        execution: '',
        done: 'success',
        cancel: 'danger'
      }
      return color[status]
    },
    resultFilter (answer, question) {
      const count = question.answers.reduce((sum, item) => { return sum + item.userAnswersCount }, 0)
      if (count === 0) {
        return 0
      }
      return (100 * answer.userAnswersCount / count).toFixed(2)
      // return answer.userAnswersCount/count
    },
    resultBackgroundFilter (answer, question) {
      const count = question.answers.reduce((sum, item) => { return sum + item.userAnswersCount }, 0)
      return 'width:' + 100 * answer.userAnswersCount / count + '%;'
      // return answer.userAnswersCount/count
    },
    statusFilter (status) {
      return Status[status]
    }
  },
  props: {
    value: {
      type: Object
    }
  },
  data () {
    return {
      voidVisible: false,
      answer: {},
      selectStatusOptions
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    }),
    guest () {
      if (this.$store.getters.user.allPermissions.includes('guest')) {
        return true
      }
      return false
    },
    mobile () {
      if (this.device === 'mobile') {
        return true
      }
      return false
    }
  },
  mounted () {
  },
  methods: {
    confirmAnswer (id) {
      const data = {
        answer_id: id
      }
      userAddAnswerToVoting(data)
        .then(response => {
          this.voidVisible = false
          if (response.data.status) {
            this.$message({
              message: 'Успешно',
              type: 'success',
              duration: 5 * 1000
            })
          } else {
            this.$message({
              message: response.data.data,
              type: 'error',
              duration: 5 * 1000
            })
          }
          this.$emit('changeResult')
        })
    },
    vote (answer, question) {
      if (!question.myAnswers && !this.guest) {
        this.answer = answer
        this.voidVisible = true
      }
    },
    ansverTextFilter (val) {
      let maxLen = 53
      if (this.mobile) {
        maxLen = 25
      }
      if (val.length > maxLen) {
        return val.substr(0, maxLen) + '...'
      }
      return val
    }
  }
}
</script>

<style scoped>
  .file-size {
    padding-left: 10px;
    color: #999;
  }
  .file-list-header {
    font-weight: bold;
    color: #1f2d3d;
  }
  .question{
    margin-top: 10px;
    /*background-color: #cca0ff;*/
    background: rgb(13,0,236);
    background: linear-gradient(315deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);
    padding: 20px;
    max-width: 640px;
    border-radius: 10px;
  }
  .answer {
    max-width: 600px;
    background-color: #86468f;
    background: rgb(13,0,236);
    background: linear-gradient(135deg, rgb(13, 0, 236) 0%, rgb(0, 112, 196) 0%, rgb(73, 112, 143) 100%);
    color: white;
    /*padding: 5px;*/
    border-radius: 5px;
    margin-bottom: 2px;
    position: relative;
    height: 36px;
  }
  .an{
    position: absolute;
    background-color: #3b576d;
    /*width: 100px;*/
    height: 100%;
    /*word-wrap: break-word;*/
    white-space:nowrap;
    /*padding: 10px;*/
    /*display: inline;*/
    border-radius: 5px 0 0 5px;
    clear: both;
  }
  .an-text{
    position: absolute;
    /*background-color: #0070c4;*/
    /*width: 100px;*/
    height: 100%;
    /*word-wrap: break-word;*/
    white-space:nowrap;
    padding: 10px;
    /*display: inline;*/
    border-radius: 5px 0 0 5px;
  }
  .cursor_pointer {
    cursor: pointer;
  }
</style>
