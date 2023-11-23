<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-md justify-between">
      <div class="vertical-middle full-height ">
        <q-btn :icon="icon" size="28px" flat :class="`text-${color}`" />
      </div>
      <div class="">
        <div class="q-pa-sm text-weight-bold">
          {{ label }}
        </div>
        <div class="text-center text-big-50 text-weight-bold">
          {{ countLabel }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default defineComponent({
  components: {},
  props: {
    label: {
      type: String,
      default: ''
    },
    icon: {
      type: [String],
      default: 'article'
    },
    color: {
      type: String,
      default: 'primary'
    },
    countLabel: {
      type: [String, Number],
      default: ''
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()

    onMounted(() => {

    })
    return {
      data
    }
  }
})
</script>

<style scoped>

</style>

