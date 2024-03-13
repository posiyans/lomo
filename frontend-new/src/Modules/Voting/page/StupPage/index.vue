<template>
  <div>
    <div class="page-title">Голосования</div>
    <div class="text-center">
      <div class="q-pa-sm">
        <img src="~assets/errors/in-developing.jpg" alt="Страница в стадии разработки" style="max-height: 50vh;">
      </div>
      <div class="text-big-60 q-pa-md text-weight-bold text-negative">
        Страница в стадии разработки
      </div>
    </div>

  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    return {
      data
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
