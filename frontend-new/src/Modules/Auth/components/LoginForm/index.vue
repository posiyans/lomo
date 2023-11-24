<template>
  <div>
    <q-form
      @submit="login"
      class="q-gutter-md"
      ref="loginForm"
    >
      <q-input
        filled
        v-model="form.email"
        label="E-mail"
        lazy-rules
        :rules="[
          isEmail,
          required
        ]"
      />

      <q-input
        filled
        type="password"
        v-model="form.password"
        label="Пароль"
        lazy-rules
        :rules="[
          required
        ]"
      />
      <q-checkbox v-model="form.remember" label="Запомнить меня" />

      <div>
        <q-btn label="Войти" :loading="authStore.loginLoading" type="submit" color="primary" class="full-width" />
      </div>
    </q-form>
    <div class="row items-center q-pa-lg">
      <div class="">
        или через
      </div>
      <LoginByVkBtn />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, reactive, ref } from 'vue'
import LoginByVkBtn from 'src/Modules/SocialMedia/vendors/Vk/components/LoginByVkBtn/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { isEmail, required } from 'src/utils/validators'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenu'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {
    LoginByVkBtn
  },
  props: {},
  setup(props, { emit }) {
    const loginForm = ref(null)
    const siteMenuStore = useSiteMenuStore()
    const form = reactive({
      email: 'posiyans@yandex.ru',
      password: 'Q12345678',
      remember: true
    })
    const authStore = useAuthStore()
    const $q = useQuasar()
    const login = () => {
      loginForm.value.validate()
        .then(status => {
          if (status) {
            authStore.login(form)
              .then(res => {
                if (res.status === 'done') {
                  siteMenuStore.getSiteMenu()
                  emit('success')
                } else {
                  $q.notify({
                    message: res.error,
                    color: 'negative'
                  })
                }
              })
          }
        })
    }
    onMounted(() => {

    })
    return {
      authStore,
      loginForm,
      isEmail,
      required,
      form,
      login
    }
  }
})
</script>

<style scoped>

</style>
