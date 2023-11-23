<template>
  <div>
    <div class="page-title">Личный кабинет</div>
    <q-btn label="Участок" to="/personal-area/stead" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default defineComponent({
  components: {},
  props: {},
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
