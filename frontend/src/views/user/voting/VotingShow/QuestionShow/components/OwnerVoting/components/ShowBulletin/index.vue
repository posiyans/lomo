<template>
  <div class="block">
    <div
      v-for="(question, i) in voting.questions"
      :key="question.id"
      class="question-block"
    >
      <strong>{{ ++i }}. {{ question.text }}</strong>
      <div>
        <div>
          <div
            v-for="(answer, j) in question.answers"
            :key="'a' + answer.id"
            class="answer-block"
          >
            <div>
              <div :label="answer.id" border> {{ ++j }}. {{ answer.text }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    voting: {
      type: Object,
      default: () => ({})
    },
    readonly: {
      type: Boolean,
      default: true
    },
    header: {
      type: Boolean,
      default: true
    }

  },
  data() {
    return {
      radio: {}
    }
  },
  methods: {
    setAnswer() {
      this.$emit('setAnswer', this.radio)
    }
  }
}
</script>

<style scoped>
  .answer-block {
    margin-left: 20px;
    margin-top: 10px;
    text-transform: uppercase;

  }
  .answer-text {
    width: 150px;
    display: inline-block;
  }
  .block {
    background-color: white;
    padding-top: 15px;
    padding-bottom: 0;
  }
  .question-block {
    margin: 0px;
    margin-bottom: 20px;
    background-color: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
  }
  .custom-checkbox {
    position: absolute;
    z-index: -1;
    opacity: 0;
  }
</style>
