<template>
  <div class="q-pa-sm row justify-center">
    <q-card>
      <q-card-section>
        <div class="text-h6 text-weight-bold">
          Сбросить пароль
        </div>
      </q-card-section>
      <q-card-section>
        <div>
          <div class="q-pb-sm">
            Для восстановления пароля введите email использованный при регистрации
          </div>
          <div style="width: 450px; max-width: 90vw;">
            <q-form
              ref="formRef"
              @submit="onSubmit"
              class="q-gutter-md"
            >
              <q-input
                v-model="email"
                label="Email"
                :rules="[isEmail]"
                outlined
              />
              <q-btn color="primary" :loading="loading" no-caps label="Отправить ссылку для сброса пароля" type="submit" />
            </q-form>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { passwordReset } from 'src/Modules/Auth/api/auth'
import { isEmail } from 'src/utils/validators'
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'

export default defineComponent({
  setup() {
    const email = ref('posiyans@yandex.ru')
    const $q = useQuasar()
    const formRef = ref(null)
    const $router = useRouter()
    const loading = ref(false)
    const onSubmit = () => {
      formRef.value.validate()
        .then(success => {
          if (success) {
            const data = {
              email: email.value
            }
            loading.value = true
            passwordReset(data)
              .then(response => {
                if (response.data) {
                  $q.dialog({
                    title: 'Успех',
                    message: 'На введенную вами почту было отправлено письмо со ссылкой для востановления пароля.'
                  }).onOk(() => {
                    $router.push({ name: 'UserLogin' })
                  })
                } else {
                  $q.dialog({
                    title: 'Ошибка',
                    color: 'negative',
                    message: 'Указанная вами почта не найдена, или сервис временно не доступен. Просьба проверить адрес почты или повторить позднее.'
                  }).onOk(() => {
                  })
                }
              })
              .finally(() => {
                loading.value = false
              })
          }
        })
    }
    return {
      isEmail,
      email,
      formRef,
      loading,
      onSubmit
    }
  }
})
</script>

<style scoped>

</style>
