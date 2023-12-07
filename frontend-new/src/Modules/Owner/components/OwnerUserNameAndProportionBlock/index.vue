<template>
  <div class="row items-center q-col-gutter-xs">
    <div>
      {{ owner.fullName }}
    </div>
    <div v-if="proportionShow" class="text-red">
      {{ owner.proportion }}%
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    owner: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const proportionShow = computed(() => {
      return !!(props.owner.proportion && props.owner.proportion !== 100);
    })
    return {
      data,
      proportionShow
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
