<template>
  <div class="q-pa-sm">
    <div>
      <AllowPublicationOfArticlesForm />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import AllowPublicationOfArticlesForm from 'src/Modules/Article/Article/components/AllowPublicationOfArticlesForm/index.vue'

export default defineComponent({
  components: {
    AllowPublicationOfArticlesForm
  },
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
