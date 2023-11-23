<template>
  <div v-if="show" class="fixed-bottom bg-grey-2 items-center justify-center cookie no-wrap">
    <div class="q-pa-lg">
      Продолжая использовать наш сайт, вы даете согласие на обработку файлов cookie, пользовательских данных (сведения о местоположении; тип и версия ОС; тип и версия Браузера; тип устройства и
      разрешение его экрана; источник откуда пришел на сайт пользователь; с какого сайта или по какой рекламе; язык ОС и Браузера; какие страницы открывает и на какие кнопки нажимает пользователь;
      ip-адрес) в целях функционирования сайта, проведения ретаргетинга и проведения статистических исследований и обзоров. Если вы не хотите, чтобы ваши данные обрабатывались, покиньте сайт.
    </div>
    <div class="q-px-lg q-py-sm text-center">
      <q-btn color="primary" label="Ok" @click="setAccepted" />
    </div>
  </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'
import { LocalStorage } from 'quasar'

export default defineComponent({
  setup() {
    const show = ref(true)
    const key = 'cookieAccept'
    onMounted(() => {
      if (LocalStorage.has(key)) {
        show.value = LocalStorage.getItem(key)
      }
    })
    const setAccepted = () => {
      LocalStorage.set(key, false)
      show.value = false
    }
    return {
      setAccepted,
      show
    }
  }
})
</script>

<style scoped lang="scss">

.cookie {
  z-index: 1000;
  display: flex;
  font-size: .95em;
  @media (max-width: 1150px) {
    font-size: .85em;
  }
  @media (max-width: 1000px) {
    font-size: .7em;
  }
  @media (max-width: 568px) {
    display: block;
    font-size: .85em;
    align-content: center;
  }
}
</style>
