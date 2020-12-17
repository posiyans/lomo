<template>
  <div class="question">
    <div style="padding-bottom: 15px; color: black; font-weight: 600;">{{ i+1 }}.
      <span> {{ question.text }}</span>
    </div>
    <div v-for="(answer, j) in question.answers" :key="answer.id">
      <div class="answer">
        <div class="an" :style="answer | resultBackgroundFilter(question)">
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
    </div>
    <div style="padding-left: 10px; padding-top: 15px;">
      Проголосовало {{ question.answersCount }} {{ question.answersCount | declOfNum(['участок', 'участка', 'участков']) }} ({{ (100*question.answersCount/steatCount).toFixed(2) }}% )
    </div>
  </div>
</template>

<script>
// Отрисовка общего результата
export default {
  filters: {
    resultBackgroundFilter(answer, question) {
      const count = question.answers.reduce((sum, item) => { return sum + item.userAnswersCount }, 0)
      return 'width:' + 600 * answer.userAnswersCount / count + 'px;'
      // return answer.userAnswersCount/count
    },
    resultFilter(answer, question) {
      const count = question.answers.reduce((sum, item) => { return sum + item.userAnswersCount }, 0)
      if (count === 0) {
        return 0
      }
      return (100 * answer.userAnswersCount / count).toFixed(2)
    }
  },
  props: {
    question: {
      type: Object,
      default: () => {}
    },
    i: {
      type: Number,
      default: 1
    },
    steatCount: {
      type: Number,
      default: 1
    }
  },
  methods: {
    ansverTextFilter(val) {
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
  /*.question{*/
  /*  margin-top: 10px;*/
  /*  !*background-color: #cca0ff;*!*/
  /*  background: rgb(13,0,236);*/
  /*  background: linear-gradient(315deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);*/
  /*  padding: 20px;*/
  /*  width: 640px;*/
  /*  border-radius: 10px;*/
  /*}*/

  /*.answer {*/
  /*  width: 600px;*/
  /*  background-color: #86468f;*/
  /*  background: rgb(13,0,236);*/
  /*  background: linear-gradient(135deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);*/
  /*  color: white;*/
  /*  !*padding: 5px;*!*/
  /*  border-radius: 5px;*/
  /*  margin-bottom: 2px;*/
  /*}*/

  /*.an{*/
  /*  background-color: #0070c4;*/
  /*  !*width: 100px;*!*/
  /*  height: 100%;*/
  /*  !*word-wrap: break-word;*!*/
  /*  white-space:nowrap;*/
  /*  padding: 10px;*/
  /*  !*display: inline;*!*/
  /*  border-radius: 5px 0 0 5px;*/
  /*}*/
  /*.file-size {*/
  /*  padding-left: 10px;*/
  /*  color: #999;*/
  /*}*/
  /*.file-list-header {*/
  /*  font-weight: bold;*/
  /*  color: #1f2d3d;*/
  /*}*/
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
</style>
