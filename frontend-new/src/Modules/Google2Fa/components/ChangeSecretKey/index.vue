<template>
  <div>
    <div @click="changeKey">
      <slot>
        <q-btn label="Обновить SecretKey" flat color="primary" />
      </slot>
    </div>
    <q-dialog v-model="dialogVisible" persistent maximized>
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Обновить SecretKey</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section v-if="secret">
          <div class="text-center">
            Ваш секретный ключ
          </div>
          <div class="text-center">
            <div class="q-pa-md text-weight-bold text-h6" style="font-family: monospace;">
              <div v-for="line in showText" :key="line" class="row items-center q-col-gutter-md justify-center">
                <div v-for="item in line" :key="item">{{ item }}</div>
              </div>
            </div>
            <div class="row justify-center q-pa-md">
              <VueQRCodeComponent :text="qrCodeText" />
            </div>
            <div>
              1.Загрузите приложение Google Authenticator на свое устройство и откройте его.
            </div>
            <div>
              2. Найдите и нажмите знак «+».
            </div>
            <div>
              3. Отсканируйте Qr-code или введите ключ, чтобы добавить свою учетную запись в Google Authenticator.
            </div>
            <div class="q-pa-md">
              <q-btn color="negative" label="Закрыть" v-close-popup />
            </div>
          </div>
        </q-card-section>
        <q-card-section v-else class="row justify-center">
          <div class="text-center" style="max-width: 600px;">
            <div class="q-pa-lg">
              Для смены SecretKey введите текущий пароль от учетной записи
            </div>
            <div>
              <q-input v-model="password" label="Пароль" outlined dense type="password" />
            </div>
            <div class="text-right q-pa-md">
              <q-btn color="primary" :disable="!password" label="Обновить" @click="updateKey" />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { changeGoogle2FaSecretKey } from 'src/Modules/Google2Fa/api/google2fa'
import VueQRCodeComponent from 'vue-qrcode-component'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { useQuasar } from 'quasar'


export default defineComponent({
  components: {
    VueQRCodeComponent
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const dialogVisible = ref(false)
    const password = ref('')
    const secret = ref(null)
    const showText = computed(() => {
      const data = []
      let pos = 0
      for (let i = 0; i < 2; i++) {
        const row = []
        for (let j = 0; j < 4; j++) {
          row.push(secret.value.substr(pos, 4))
          pos = pos + 4
        }
        data.push(row)
      }
      return data
    })
    const authStote = useAuthStore()
    const qrCodeText = computed(() => {
      return 'otpauth://totp/' + authStote.user.email + '?issuer=' + window.location.hostname + '&secret=' + secret.value
    })
    const changeKey = () => {
      secret.value = null
      dialogVisible.value = true
    }
    const $q = useQuasar()
    const updateKey = () => {
      const data = {
        password: password.value
      }
      changeGoogle2FaSecretKey(data)
        .then(res => {
          secret.value = res.data.secret
        })
        .catch(er => {
          $q.notify({
            message: er.response.data.error,
            type: 'negative'
          })
        })
    }
    return {
      data,
      dialogVisible,
      password,
      secret,
      showText,
      qrCodeText,
      changeKey,
      updateKey
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
