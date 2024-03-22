<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn label="Подробнее" color="primary" />
      </slot>
    </div>
    <q-dialog
      v-model="dialogVisible"
      :maximized="dialogMaximized"
      @hide="reload"

    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6 row items-center q-col-gutter-xs">
            <div>
              Платеж на
            </div>
            <ShowPrice :price="payment.price" class="text-primary text-weight-bold" />
            <div>
              от
            </div>
            <ShowTime :time="payment.payment_date" format="DD-MM-YYYY" />
          </div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <PaymentInfoShowAndEdit :payment="payment" :edit="edit" @reload="reload" @deletePayment="deletePayment" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import PaymentInfoShowAndEdit from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import ShowTime from 'components/ShowTime/index.vue'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {
    PaymentInfoShowAndEdit,
    ShowPrice,
    ShowTime
  },
  props: {
    payment: {
      type: Object,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const showDialog = () => {
      dialogVisible.value = true
    }
    const $q = useQuasar()
    const dialogMaximized = computed(() => {
      return $q.screen.width < 600
    })
    const reload = () => {
      emit('reload')
    }
    const deletePayment = () => {
      dialogVisible.value = false
      emit('deletePayment')
    }
    return {
      reload,
      showDialog,
      deletePayment,
      dialogMaximized,
      dialogVisible
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
