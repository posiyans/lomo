<template>
  <div class="q-pa-sm row justify-center">
    <q-card>
      <q-card-section>
        <div class="text-h6 text-center text-weight-bold">
          Войти на сайт
        </div>
      </q-card-section>
      <q-card-section>
        <LoginForm style="width: 450px;  max-width: 90vw;" @success="goHome" />
      </q-card-section>
      <q-card-section>
        <div class="text-right">
          <router-link :to="{name: 'UserResetPassword'}" class="text-primary   text-weight-bold reset-password-link">
            Забыли пароль?
          </router-link>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import LoginForm from 'src/Modules/Auth/components/LoginForm/index.vue'
import { useRouter } from 'vue-router'

export default defineComponent({
  components: {
    LoginForm
  },
  props: {},
  setup(props, { emit }) {
    const $router = useRouter()
    const goHome = () => {
      $router.push('/')
    }
    return {
      goHome
    }
  }
})
</script>

<style scoped>

</style>
