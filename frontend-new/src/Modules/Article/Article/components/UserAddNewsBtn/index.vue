<template>
  <div>
    <q-btn @click="toAddNews">Предложить запись</q-btn>
<!--    <div v-if="showForm" class="q-mb-lg">-->
<!--      <UserOfferNews />-->
<!--    </div>-->
  </div>
</template>

<script>
// import UserOfferNews from 'src/Modules/Article/Article/page/UserOfferNews'
export default {
  components: {
    // UserOfferNews
  },
  data () {
    return {
      showForm: false
    }
  },
  computed: {
    user () {
      return this.$store.state.user
    }
  },
  methods: {
    toAddNews () {
      if (this.user && this.user.info.consent_to_email) {
        this.showForm = !this.showForm
      } else {
        this.$alert('Предлагать новости могут только зарегистрированные пользователи с подтвежденным адресом электронной почты!', 'Ошибка', {
          confirmButtonText: 'OK'
        })
      }
      // this.$router.push('/article/add-news')
    }
  }
}
</script>

<style scoped>

</style>
