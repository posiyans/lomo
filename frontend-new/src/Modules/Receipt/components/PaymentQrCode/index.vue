<template>
  <div class="text-center rounded-borders">
    <div class="text-h4 q-pa-md">QR code для оплаты</div>
    <div class="q-ma-lg" @click="dialog = true">
      <q-img :src="urlQRcode">
        <template v-slot:loading>
          <div class="text-primary">
            <q-spinner-ios />
            <div class="q-mt-md">Loading...</div>
          </div>
        </template>
      </q-img>
    </div>
    <div class="q-pa-md">Для оплаты, отсканируйте код в банк клиенте.</div>
    <q-dialog
      v-model="dialog"
      maximized
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card class="">
        <q-bar>
          <q-space />
          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>
        <q-card-section class="q-pa-none">
          <div class="text-center">
            <img :src="urlQRcode" :width="width" :height="height" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const data = ref(null)
    const dialog = ref(false)
    const router = useRouter()
    const route = useRoute()
    const q = useQuasar()
    const width = computed(() => {
      if (q.screen.width > q.screen.height) {
        return null
      }
      return q.screen.width
    })
    const height = computed(() => {
      if (q.screen.width > q.screen.height) {
        return q.screen.height - 40
      }
      return null
    })
    const urlQRcode = computed(() => {
      return process.env.BASE_API + '/api/v2/receipt/qrcode'
    })
    onMounted(() => {

    })
    return {
      data,
      dialog,
      width,
      height,
      urlQRcode
    }
  }
})
</script>

<style scoped>

</style>
