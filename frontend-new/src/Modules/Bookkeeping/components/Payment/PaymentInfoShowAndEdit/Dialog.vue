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
      full-width
    >
      <q-card v-if="firstLoading">
        <q-card-section class="flex flex-center fit text-primary" style="min-height: 50vh;">
          <div class="relative-position">
            <q-spinner
              size="25vh"
              :thickness="5"
            />
            <div class="fit absolute-top flex flex-center">
              <div class="o-60">
                Загрузка...
              </div>
            </div>
          </div>
        </q-card-section>
      </q-card>
      <div v-else>
        <transition
          appear
          enter-active-class="animated fadeIn"
          leave-active-class="animated fadeOut"
        >
          <q-card style="min-height: 50vh;">
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
        </transition>
      </div>
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
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment.js'

export default defineComponent({
  components: {
    PaymentInfoShowAndEdit,
    ShowPrice,
    ShowTime
  },
  props: {
    paymentId: {
      type: [Number, String],
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const { payment, getPaymentData, setPaymentId, loading, firstLoading } = useCurrentPayment()
    const dialogVisible = ref(false)
    const showDialog = () => {
      dialogVisible.value = true
      setPaymentId(props.paymentId)
      getPaymentData()
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
      payment,
      loading,
      firstLoading,
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
