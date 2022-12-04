<template>
  <div v-for="(answer, i) in list" :key="answer.id">
    <slot v-bind:answer="answer" v-bind:i="i">
      {{answer.text}}
    </slot>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from 'vue'
import { getAnswersForQuestion } from 'src/Modules/Voting/api/voting'

export default defineComponent({
  props: {
    questionId: {
      type: [String, Number],
      default: ''
    },
    addNoAnswer: {
      type: Boolean,
      default: false
    }
  },
  setup (props) {
    const list = ref([])
    onMounted(() => {
      getData()
    })
    watch(
      () => props.questionId,
      () => {
        getData()
      }
    )
    const getData = () => {
      const data = {
        id: props.questionId
      }
      getAnswersForQuestion(data)
        .then(res => {
          list.value = res.data
          if (props.addNoAnswer) {
            list.value.push(
              {
                id: '',
                text: 'Не голосовал'
              }
            )
          }
        })
    }
    return {
      list
    }
  }
})
</script>

<style scoped>

</style>
