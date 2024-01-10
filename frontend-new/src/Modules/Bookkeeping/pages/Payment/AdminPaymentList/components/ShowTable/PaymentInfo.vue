<template>
  <div>
    <q-btn @click="showDialog" label="Подробнее" color="primary" />
    <q-dialog v-model="dialogVisible">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Платеж</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          {{ payment }}
          <PaymentInfoShowAndEdit :payment="payment" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import PaymentInfoShowAndEdit from 'src/Modules/Bookkeeping/components/Payment/PaymnetInfoShowAndEdit/index.vue'

export default defineComponent({
  components: {
    PaymentInfoShowAndEdit
  },
  props: {
    payment: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const dialogVisible = ref(false)
    const showDialog = () => {
      dialogVisible.value = true
    }
    return {
      data,
      showDialog,
      dialogVisible
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
