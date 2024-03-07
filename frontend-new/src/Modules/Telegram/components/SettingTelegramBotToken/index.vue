<template>
  <div>
    <div v-if="loading" class="q-pa-md" style="max-width: 300px;">
      <q-skeleton
        animation="wave"
        type="QToolbar"
      />
    </div>
    <div v-else>
      <div v-if="noBot" class="row items-center q-col-gutter-sm">
        <div class="text-red-10 q-pa-md">
          Телеграм Бот не настроен или неверный токен
        </div>
        <div v-if="superAdmin">
          <q-btn icon="settings" color="primary" flat @click="showSetting" />
        </div>
      </div>
      <div v-if="!noBot">
        <q-card>
          <q-card-section>
            <div class="q-gutter-sm">
              <div class="row items-center">
                <div class="text-primary text-weight-bold ">
                  Телеграм бот
                </div>
                <div>
                  <q-btn v-if="superAdmin" icon="settings" color="primary" flat @click="showSetting" />
                </div>
              </div>
              <div>
                <div class="row items-center q-col-gutter-sm">
                  <div>
                    Имя бота:
                  </div>
                  <div class="text-primary">
                    {{ bot.first_name }}
                  </div>
                </div>
              </div>
              <div>
                <div class="row items-center q-col-gutter-sm">
                  <div>
                    Сылка на бот:
                  </div>
                  <div class="text-primary cursor-pointer" @click="openBot">
                    @{{ bot.username }}
                  </div>
                </div>
              </div>
              <div>
                <q-checkbox
                  v-model="bot.two_factor_telegram"
                  label="Двухэтапная аутентификация через бота"
                  :disable="!superAdmin"
                  @update:model-value="changeBotEnable"
                />
              </div>
            </div>
            <q-card v-if="settingVisible" style="max-width: 600px;">
              <q-card-section>
                <div class="q-gutter-sm">
                  <div class=''>
                    <q-input v-model="token" outlined label="Сменить токен бота" />
                  </div>
                  <div class="q-gutter-sm">
                    <q-btn label="Отмена" color="negative" flat @click="settingVisible = false" />
                    <q-btn label="Сменить" color="primary" @click="saveToken" />
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { changeTwoFactorEnable, getTelegramBotInfo, updateTelegramBotToken } from 'src/Modules/Telegram/api/telegram.js'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const token = ref('')
    const settingVisible = ref(false)
    const loading = ref(true)
    const noBot = ref(false)
    const bot = ref({})
    const authStore = useAuthStore()
    const superAdmin = computed(() => {
      return authStore.roles.includes('superAdmin')
    })
    const changeBotEnable = () => {
      const data = {
        name: 'telegram',
        value: bot.value.two_factor_telegram
      }
      changeTwoFactorEnable(data)
        .then(() => {
          getInfo()
        })
    }
    const openBot = () => {
      window.open('https://t.me/' + bot.value.username, '_blank')
    }
    const showSetting = () => {
      settingVisible.value = true
    }
    const getInfo = () => {
      getTelegramBotInfo()
        .then(res => {
          if (res.data.status) {
            bot.value = res.data.data
            noBot.value = false
          } else {
            noBot.value = true
          }
        })
        .finally(() => {
          loading.value = false
        })
    }
    const $q = useQuasar()
    const saveToken = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Подтвердите изменение токена?',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Измениь',
          color: 'primary'
        },
        persistent: true
      }).onOk(() => {
        const data = {
          token: token.value
        }
        updateTelegramBotToken(data)
          .then(res => {
            settingVisible.value = false
            loading.value = true
            getInfo()
            token.value = ''
            $q.notify({
              type: 'secondary',
              message: 'Ok'
            })
          })
      })


    }
    onMounted(() => {
      getInfo()
    })

    return {
      token,
      loading,
      bot,
      changeBotEnable,
      saveToken,
      openBot,
      noBot,
      getInfo,
      showSetting,
      settingVisible,
      superAdmin
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
