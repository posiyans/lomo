<template>
  <div class="relative-position answer-item">
    <div class="absolute-left full-height an" :style="styleBlock" />
    <div class="absolute-left row text-white q-px-md q-pt-sm full-height">
      <div class="q-mr-sm">
        {{ i }}.
      </div>
      <div>
        {{answer.title}} - {{answer.votes}}
        <span class="text-yellow-5">{{answer.procent}}%</span>
      </div>
    </div>

  </div>
</template>

<script>
import { defineComponent, computed } from 'vue'

export default defineComponent({
  props: {
    answer: {
      type: Object,
      required: true
    },
    i: {
      type: [Number, String],
      required: true
    }
  },
  setup (props) {
    const styleBlock = computed(() => {
      return 'width: ' + props.answer.procent + '%;'
    })
    return {
      styleBlock
    }
  }
})
</script>

<style scoped>
.answer-item {
  width: 100%;
  background: linear-gradient(135deg, rgb(13, 0, 236) 0%, rgb(0, 112, 196) 0%, rgb(73, 112, 143) 100%);
  border-radius: 5px;
  height: 36px;
}
.an{
  position: absolute;
  background-color: #3b576d;
  height: 100%;
  white-space: nowrap;
  border-radius: 5px 0 0 5px;
}
</style>
