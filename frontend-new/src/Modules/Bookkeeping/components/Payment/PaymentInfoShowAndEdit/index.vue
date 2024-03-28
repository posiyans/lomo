<template>
  <div>
    <div>
      <q-linear-progress
        v-if="loading"
        indeterminate
        size="2px"
      />
    </div>
    <q-markup-table
      separator="cell"
      wrap-cells
      bordered
      flat
      class="full-width"
    >
      <thead>
      <tr class="bg-black-05">
        <th>Поле</th>
        <th>
          <div class="row items-center" style="min-width: 250px;">
            <div class="col-grow">
              <div>
                Значение
              </div>
            </div>
            <div>
              <q-fab
                v-if="edit"
                flat
                text-color="black"
                icon="more_vert"
                direction="left"
                padding="xs"
              >
                <PaymentDeleteBtn :payment-id="payment.id" @success="deletePayment">
                  <q-fab-action color="negative" flat icon="delete" />
                </PaymentDeleteBtn>
              </q-fab>

            </div>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td>id {{ payment.id }}</td>
        <td>
          <ShowTime :time="payment.payment_date" before="от " format="DD-MM-YYYY HH:mm" />
        </td>
      </tr>
      <TrStead :edit="edit" />
      <TrOwnerInfo :edit="edit" />
      <tr>
        <td>Сумма</td>
        <td>
          <ShowPrice :price="payment.price" />
        </td>
      </tr>
      <TrDestination />
      <TrTypePayment :edit="edit" />
      <TrReading
        :edit="edit"
      />
      <TrDescription :edit="edit" />
      </tbody>
    </q-markup-table>
    <PaymentDateVerifiedBtn v-if="edit" class="q-pa-sm" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import PaymentDeleteBtn from 'src/Modules/Bookkeeping/components/Payment/PaymentDeleteBtn/index.vue'
import TrReading from './components/TrReading/index.vue'
import TrStead from './components/TrStead/index.vue'
import TrOwnerInfo from './components/TrOwnerInfo/index.vue'
import TrDestination from './components/TrDestination/index.vue'
import TrTypePayment from './components/TrTypePayment/index.vue'
import TrDescription from './components/TrDescription/index.vue'
import PaymentDateVerifiedBtn from './components/PaymentDateVerifiedBtn/index.vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'

export default defineComponent({
  components: {
    ShowTime,
    PaymentDeleteBtn,
    ShowPrice,
    TrReading,
    TrStead,
    TrOwnerInfo,
    TrDestination,
    TrTypePayment,
    TrDescription,
    PaymentDateVerifiedBtn
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const { payment, getPaymentData, loading } = useCurrentPayment()

    const deletePayment = () => {
      emit('deletePayment')
    }

    return {
      payment,
      loading,
      deletePayment
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
