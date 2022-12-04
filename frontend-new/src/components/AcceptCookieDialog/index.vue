<template>
  <div>
    <q-dialog v-model="showDialog" seamless full-width position="bottom" >
      <q-card class="bg-amber-1">
        <q-card-section class="row items-center no-wrap ">
          <div>
            <div class="text-weight-bold">Cookie</div>
            <div class="text-grey-8">Продолжая использовать наш сайт, вы даете согласие на обработку файлов cookie, пользовательских данных (тип и версия ОС; тип и версия Браузера; тип устройства и разрешение его экрана; язык ОС и Браузера; какие страницы открывает и на какие кнопки нажимает пользователь; ip-адрес) в целях функционирования сайта. Если вы не хотите, чтобы ваши данные обрабатывались, покиньте сайт.</div>
          </div>
          <q-space />
          <q-btn flat round icon="close" v-close-popup @click="closeDialog" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { LocalStorage } from 'quasar'
export default defineComponent({
  setup () {
    const showDialog = ref(false)
    const keyName = 'AcceptCookieDialog'
    onMounted(() => {
      showDialog.value = !LocalStorage.getItem(keyName)
    })
    const closeDialog = () => {
      showDialog.value = false
      LocalStorage.set(keyName, true)
    }
    return {
      showDialog,
      closeDialog
    }
  }
})
</script>

<style scoped>

</style>
