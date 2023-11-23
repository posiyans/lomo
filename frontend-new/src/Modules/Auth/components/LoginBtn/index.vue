<template>
  <div>
    <q-btn label="Войти" color="white" text-color="black" @click="showLoginForm = !showLoginForm" />
    <q-dialog
      :maximized="mobile"
      v-model="showLoginForm"
    >
      <q-card>
        <q-card-section class="bg-grey-9 text-white ">
          <div class="text-h6 text-center relative-position">
            Войти на сайт
            <div class="absolute-right">
              <q-btn icon="open_in_new" flat :to="{name: 'UserLogin'}" />
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <LoginForm style="width: 450px;  max-width: 90vw;" @success="showLoginForm = false" />
          <div class="text-right">
            <router-link :to="{name: 'UserResetPassword'}" class="text-primary   text-weight-bold reset-password-link">
              Забыли пароль?
            </router-link>
          </div>
        </q-card-section>
        <q-card-section class="bg-grey-9 text-white">
          <div class="text-h6 text-center">
            <q-btn color="primary" label="Закрыть" @click="showLoginForm = false" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import LoginForm from 'src/Modules/Auth/components/LoginForm/index.vue'

export default {
  components: {
    LoginForm
  },
  data() {
    return {
      showLoginForm: false
    }
  },
  computed: {
    mobile() {
      return this.$q.platform.is.mobile
    }
  }
}
</script>

<style scoped lang="scss">
.reset-password-link {
  opacity: .6;

  &:hover {
    opacity: 1;
  }
}
</style>
