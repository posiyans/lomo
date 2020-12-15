<template>
  <div class="question">
    <div style="padding-bottom: 5px; color: black; font-weight: 600;">{{ i+1 }}.
      <span> {{ question.text }}</span>
    </div>
    <div style="padding-bottom: 5px;">Проголосовало {{ (100*question.answersCount/steatCount).toFixed(2) }}%  ({{ question.answersCount }} участков)</div>
    <div v-for="(answer, j) in question.answers">
      <div class="answer">
        <div class="an" :style="answer | resultBackgroundFilter(question)">
          {{ j+1 }}.<span>{{ answer.text }} </span> <span>{{ answer.userAnswersCount }} </span><span> ({{ answer | resultFilter(question) }}%)</span>
        </div>
      </div>
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
  }
}
</script>

<style scoped>
  .question{
    margin-top: 10px;
    /*background-color: #cca0ff;*/
    background: rgb(13,0,236);
    background: linear-gradient(315deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);
    padding: 20px;
    width: 640px;
    border-radius: 10px;
  }

  .answer {
    width: 600px;
    background-color: #86468f;
    background: rgb(13,0,236);
    background: linear-gradient(135deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);
    color: white;
    /*padding: 5px;*/
    border-radius: 5px;
    margin-bottom: 2px;
  }

  .an{
    background-color: #0070c4;
    /*width: 100px;*/
    height: 100%;
    /*word-wrap: break-word;*/
    white-space:nowrap;
    padding: 10px;
    /*display: inline;*/
    border-radius: 5px 0 0 5px;
  }
</style>
