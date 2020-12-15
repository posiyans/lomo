<template>
  <div class="app-container">
    <h1>{{ value.title }}</h1>
    <el-tabs type="border-card">
      <el-tab-pane label="Результат">
        <el-tag :type="value.status | statusColorFilter">
          {{ value.status | statusFilter }}
        </el-tag>
        <div v-for="(question, i) in value.questions" class="question">
          <div style="padding-bottom: 5px; color: black; font-weight: 600;">{{ i+1 }}.
            <span> {{question.text}}</span>
          </div>
          <div style="padding-bottom: 5px;">Проголосовало {{question.answersCount}} пользователей</div>
          <div v-for="(answer, j) in question.answers">
            <div class="answer">
              <div class="an" :style="answer | resultBackgroundFilter(question)">
              </div>
              <div class="an-text">
                {{j+1}}. <span>{{answer.text}} </span> <span>{{answer.userAnswersCount}} </span><span> ({{answer | resultFilter(question) }}%)</span>
              </div>
            </div>
          </div>
        </div>
      </el-tab-pane>

      <el-tab-pane label="Пользователи">
        <UserAnswer v-if="value.questions" :questions="value.questions"/>
      </el-tab-pane>

      <el-tab-pane label="Описание">
        <div class="question" v-html="value.description"></div>
        <div v-if="value.files && value.files.length > 0" class="question">
          <div class="file-list-header">Файлы:</div>
          <ul>
            <li v-for="file in value.files">{{ file.name }}
              <span class="file-size">{{ file.size | sizeFilter }}</span>
              <el-link :href="file.id | urlFilter " type="success">Скачать</el-link>
            </li>
          </ul>
        </div>

      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import waves from '@/directive/waves' // waves directive
import UserAnswer from './UserAnswer.vue'

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
  name: 'AdminPublicVotingResult',
  components: {
    UserAnswer,
  },
  directives: { waves },
  filters: {
    statusColorFilter(status){
      const color = {
        new: 'info',
        execution: '',
        done: 'success',
        cancel: 'danger'
      }
      return color[status]
    },
    urlFilter(val) {
      return process.env.VUE_APP_BASE_API + '/api/user/storage/file/' + val
    },
    resultFilter(answer, question) {
      const count = question.answers.reduce( (sum, item) => {return sum + item.userAnswersCount}, 0)
      if (count === 0){
        return 0
      }
      return (100*answer.userAnswersCount/count).toFixed(2)
      // return answer.userAnswersCount/count
    },
    resultBackgroundFilter(answer, question) {
      const count = question.answers.reduce( (sum, item) => {return sum + item.userAnswersCount}, 0)
      if (count == 0){
        return 'width: 0%;'
      }
      return 'width:' + 100*answer.userAnswersCount/count + '%;'
      // return 'width: 50%;'
    },
    statusFilter(status) {
      return Status[status]
    },
  },
  props:{
    value: {
      type: Object,
      default: {}
    }
  },
  data() {
    return {
    }
  },
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
    background: linear-gradient(135deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);
    color: white;
    /*padding: 5px;*/
    border-radius: 5px;
    margin-bottom: 2px;
    position: relative;
    height: 36px;
    /*float: left;*/
  }
  .an{
    position: absolute;
    background-color: #0070c4;
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
</style>
