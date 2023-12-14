<template>
  <div v-if="showBtn">
    <UserOfferNews @success="toPrimary" />
  </div>
  <q-card v-if="authStore.is_guest">
    <q-card-section class="q-pa-lg text-center content-center page-title" style="min-height: 400px;">
      Необходима авторизация
    </q-card-section>
  </q-card>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import UserOfferNews from 'src/Modules/Article/Article/components/UserOfferNews/index.vue'
import { useRouter } from 'vue-router'
import { getAllowPublicationArticle } from 'src/Modules/Article/Article/api/article'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    UserOfferNews
  },
  props: {},
  setup(props, { emit }) {
    const router = useRouter()
    const showBtn = ref(false)
    const authStore = useAuthStore()
    const toPrimary = () => {
      router.push('/')
    }
    onMounted(() => {
      getAllowPublicationArticle({ value: 1 })
        .then(res => {
          showBtn.value = true
        })
    })
    return {
      toPrimary,
      authStore,
      showBtn
    }
  }
})
</script>

<style scoped>

</style>
