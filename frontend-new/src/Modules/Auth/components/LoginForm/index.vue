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
        :type="showPassword ? 'text' : 'password'"
        v-model="form.password"
        label="Пароль"
        lazy-rules
        :rules="[
          required
        ]"
      >
        <template v-slot:append>
          <q-icon
            :name="showPassword ? 'visibility_off' : 'visibility'"
            class="cursor-pointer"
            @click="showPassword = !showPassword"
          />
        </template>
      </q-input>
      <q-input
        ref="codeRef"
        v-show="showCodeForm"
        filled
        v-model="form.code"
        label="Код подтверждения"
        lazy-rules
        maxlength="6"
        @update:model-value="setCode"
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
import { defineComponent, nextTick, onMounted, reactive, ref } from 'vue'
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
    const codeRef = ref(null)
    const showCodeForm = ref(false)
    const showPassword = ref(false)
    const siteMenuStore = useSiteMenuStore()
    const form = reactive({
      email: '',
      password: '',
      code: '',
      remember: true
    })
    const setCode = (val) => {
      if (val.length === 6) {
        login()
      }
    }
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
                } else if (res.status === 'sendCode') {
                  showCodeForm.value = true
                  nextTick(() => {
                    codeRef.value.focus()
                  })
                } else if (res.status === 'errorCode') {
                  $q.notify({
                    message: res.error,
                    color: 'negative'
                  })
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
      showCodeForm,
      showPassword,
      isEmail,
      setCode,
      codeRef,
      required,
      form,
      login
    }
  }
})
</script>

<style scoped>

</style>
