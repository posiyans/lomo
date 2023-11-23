<template>
  <div>
    <div v-if="showForm" class="q-mb-lg relative-position">
      <UserOfferNews
        @success="showForm = false"
      />
      <div class="absolute-top-right q-ma-md">
        <q-btn icon="close" flat color="primary" @click="showForm = false" />
      </div>
    </div>
    <q-btn v-else color="primary" @click="toAddNews">Предложить запись</q-btn>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { useQuasar } from 'quasar'
import UserOfferNews from 'src/Modules/Article/Article/components/UserOfferNews/index.vue'

export default defineComponent({
  components: {
    UserOfferNews
  },
  props: {},
  setup() {
    const showForm = ref(null)
    const $q = useQuasar()
    const authStore = useAuthStore()
    const toAddNews = () => {
      if (authStore.user.email_verified_at) {
        showForm.value = true
      } else {
        $q.dialog({
          title: 'Ошибка',
          message: 'Предлагать новости могут только зарегистрированные пользователи с подтвержденным адресом электронной почты.'
        })
      }
    }
    return {
      authStore,
      showForm,
      toAddNews
    }
  }
})
</script>

<style scoped>

</style>
