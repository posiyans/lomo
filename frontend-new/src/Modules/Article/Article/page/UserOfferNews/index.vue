<template>
  <div class="bg-white">
    <div v-if="showBtn">
      <UserOfferNews @success="toPrimary" />
    </div>
    <div v-if="errors" class="q-pa-md">
      <div class="text-weight-bold text-big-50">
        Предложить новость
      </div>

      <div class="fixed-center text-center text-weight-bold text-red">
        <div class="text-big-50">
          Ошибка
        </div>
        <div>
          {{ errors }}
        </div>
      </div>
    </div>
    <q-card v-if="authStore.is_guest">
      <q-card-section class="q-pa-lg text-center content-center page-title" style="min-height: 400px;">
        Необходима авторизация
      </q-card-section>
    </q-card>
  </div>
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
    const errors = ref(false)
    const authStore = useAuthStore()
    const toPrimary = () => {
      router.push('/')
    }
    onMounted(() => {
      getAllowPublicationArticle()
        .then(res => {
          showBtn.value = true
        })
        .catch(er => {
          errors.value = er.response.data.errors
        })
    })
    return {
      toPrimary,
      authStore,
      errors,
      showBtn
    }
  }
})
</script>

<style scoped>

</style>
