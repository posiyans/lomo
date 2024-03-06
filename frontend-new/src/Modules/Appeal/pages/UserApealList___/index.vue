<template>
  <div>
    <AppealList :user-uid="userUid" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppealList from 'src/Modules/Appeal/pages/AppealList/index.vue'

export default defineComponent({
  components: {
    AppealList
  },
  props: {
    userUid: {
      type: String,
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

