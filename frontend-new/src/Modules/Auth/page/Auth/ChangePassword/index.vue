<template>
  <div class="q-pa-sm row justify-center">
    <q-card style="width: 450px;  max-width: 90vw;">
      <q-card-section>
        <div class="text-h6">
          Установить новый пароль
        </div>
      </q-card-section>
      <q-card-section>
        <div class="">
          <q-form
            ref="resetFormRef"
            @submit="submitForm"
            class="q-gutter-md"
          >
            <q-input
              v-model="data.email"
              disable
              outlined
              label="Ваш email"
            />
            <q-input
              v-model="data.password"
              outlined
              label="Пароль"
              type="password"
              :rules="[
                passwordCapital,
                passwordNumber,
                val => short(val, 8)
              ]"
            />
            <q-input
              v-model="data.password_confirmation"
              outlined
              label="Подтвердить пароль"
              type="password"
              :rules="[
                val => passwordConfirm(val, data.password)
              ]"
            />
            <q-btn label="Сохранить" type="submit" color="primary" />
          </q-form>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { passwordCapital, passwordConfirm, passwordNumber, short } from 'src/utils/validators.js'
import { changePasswordByToken } from 'src/Modules/Auth/api/auth'
import { useQuasar } from 'quasar'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const resetFormRef = ref(null)
    const loading = ref(false)
    const $q = useQuasar()
    const data = ref(
      {
        email: '',
        token: '',
        password: 'Q1234567811',
        password_confirmation: 'Q1234567811'
      }
    )

    const router = useRouter()
    const route = useRoute()
    const submitForm = () => {
      resetFormRef.value.validate()
        .then(status => {
          loading.value = true
          if (status) {
            data.value.token = route.query.token
            changePasswordByToken(data.value)
              .then(res => {
                $q.notify({
                  color: 'secondary',
                  message: 'Пароль успешно изменен'
                })
                router.push({ name: 'UserLogin' })
              })
              .catch(er => {
                errorMessage(er.response.data.errors)
              })
              .finally(() => {
                loading.value = false
              })
          }
        })
    }
    onMounted(() => {
      data.value.email = route.query.email
    })
    return {
      data,
      resetFormRef,
      submitForm,
      passwordCapital,
      passwordConfirm,
      passwordNumber,
      short
    }
  }
})
</script>

<style scoped>

</style>
