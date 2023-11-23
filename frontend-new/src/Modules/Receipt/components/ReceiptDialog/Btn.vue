<template>
  <div>
    <q-btn color="primary" label="Скачать" @click="showForm" />
    <q-dialog
      v-model="dialogVisible"
      :maximized="q.platform.is.mobile"
      style="min-width: 600px;"
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none no-wrap">
          <div class="text-h6 q-px-md">Скачать квитанцию на оплату</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section class="q-pa-md">
          <ReceiptDialog />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import ReceiptDialog from 'src/Modules/Receipt/components/ReceiptDialog/index.vue'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {
    ReceiptDialog
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(null)
    const dialogVisible = ref(false)
    const q = useQuasar()
    const showForm = () => {
      dialogVisible.value = true
    }
    onMounted(() => {

    })
    return {
      data,
      q,
      showForm,
      dialogVisible
    }
  }
})
</script>

<style scoped>

</style>
