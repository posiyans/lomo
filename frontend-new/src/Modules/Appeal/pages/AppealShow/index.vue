<template>
  <div>
    {{ appeal }}
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getAppeal } from 'src/Modules/Appeal/api/appealApi'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const appeal = ref({})
    const router = useRouter()
    const route = useRoute()
    const getData = () => {
      getAppeal(route.params.id)
        .then(res => {
          appeal.value = res.data.data
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      appeal
    }
  }
})
</script>

<style scoped>

</style>
