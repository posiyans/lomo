<template>
  <div>
    <UserAddNewsBtn class="q-pa-sm" />
    <AllArticleList />
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import AllArticleList from 'src/Modules/Article/Article/components/AllArticleList/index.vue'
import { useCounterStore } from 'src/stores/example-store.js'
import { useUserStore } from 'src/Modules/Auth/store/user-store'
import UserAddNewsBtn from 'src/Modules/Article/Article/components/UserAddNewsBtn/index.vue'

export default defineComponent({
  components: {
    AllArticleList,
    UserAddNewsBtn
  },
  setup() {
    const counterStore = useCounterStore()
    const userStore = useUserStore()
    return {
      counterStore,
      userStore
    }
  }
})
</script>
