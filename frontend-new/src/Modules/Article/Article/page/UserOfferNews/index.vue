<template>
  <div>
    <UserOfferNews @success="toPrimary" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import UserOfferNews from 'src/Modules/Article/Article/components/UserOfferNews/index.vue'
import { useRouter } from 'vue-router'

export default defineComponent({
  components: {
    UserOfferNews
  },
  props: {},
  setup(props, { emit }) {
    const router = useRouter()
    const toPrimary = () => {
      router.push('/')
    }
    return {
      toPrimary
    }
  }
})
</script>

<style scoped>

</style>
