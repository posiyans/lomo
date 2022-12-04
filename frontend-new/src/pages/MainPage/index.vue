<template>
  <div class="">
    <AllArticleList />
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import AllArticleList from 'src/Modules/Article/Article/components/AllArticleList/index.vue'
import { useCounterStore } from 'src/stores/example-store.js'
import { useUserStore } from 'src/Modules/User/store/user-store'
export default defineComponent({
  components: {
    AllArticleList
  },
  setup () {
    const counterStore = useCounterStore()
    const userStore = useUserStore()
    return {
      counterStore,
      userStore
    }
  }
})
</script>
