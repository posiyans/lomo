<template>
  <q-select
      :outlined="outlined"
      :dense="dense"
      :model-value="modelValue"
      :options="list"
      map-options
      emit-value
      option-value="id"
      option-label="text"
      :use-chips="useChips"
      :clearable="clearable"
      :multiple="multiple"
      :label="label"
      @update:modelValue="setvalue"
  />
</template>

<script>
import { defineComponent, ref, onMounted, watch } from 'vue'
import { votingStatus } from 'src/Modules/Voting/constant.js'
import { getQuestionsForVoting } from 'src/Modules/Voting/api/voting'

export default defineComponent({
  props: {
    modelValue: {
      type: [String, Number, Array]
    },
    label: {
      type: String,
      default: 'Выбрать вопрос'
    },
    dense: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    multiple: {
      type: Boolean,
      default: false
    },
    clearable: {
      type: Boolean,
      default: false
    },
    useChips: {
      type: Boolean,
      default: false
    },
    votingId: {
      type: [String, Number],
      required: true
    }
  },
  setup (props, { emit }) {
    const list = ref([])
    const setvalue = (val) => {
      emit('update:modelValue', val)
    }
    watch(
      () => props.votingId,
      () => {
        getData()
      }
    )
    onMounted(() => {
      getData()
    })
    const getData = () => {
      const data = {
        id: props.votingId
      }
      getQuestionsForVoting(data)
        .then(res => {
          list.value = res.data
        })
    }
    return {
      setvalue,
      list,
      votingStatus
    }
  }
})
</script>

<style scoped>

</style>
