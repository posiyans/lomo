<template>
  <div>
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
              <div v-if="payment.payment_type === 2" class="text-small-85 text-red">
                Платеж в кассу
              </div>
            </div>
            <div>
              <q-fab
                v-if="edit"
                flat
                text-color="black"
                icon="more_vert"
                direction="down"
                padding="xs"
              >
                <PaymentDeleteBtn :payment-id="payment.id" @success="deletePayment">
                  <q-fab-action color="negative" text-color="white" icon="delete" />
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
      <tr :class="{'bg-red-1': steadError}">
        <td>Участок</td>
        <td>
          <div v-if="findSteadShow" class="row items-center">
            <SteadSelect
              v-model="steadId"
              outlined
              dense
              clearable
            />
            <q-space />
            <div>
              <q-btn label="Ok" color="primary" @click="changeStead" />
            </div>
          </div>
          <div v-else class="row items-center">
            <div>
              <span v-if="steadError">
                {{ payment.raw_data[2] }} -->
              </span>
              <q-chip v-if="payment.stead" outline square color="primary" text-color="white">
                {{ payment.stead?.number }}
              </q-chip>
              <span v-else>?</span>
            </div>
            <q-space />
            <div v-if="edit">
              <q-btn icon="edit" flat color="primary" dense size="sm" @click="findSteadShow = !findSteadShow" />
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>ФИО</td>
        <td>
          <div class="row items-center">
            <div>
              {{ payment.raw_data[3] }}
            </div>
            <q-space />
            <FindOwnerPopup v-if="edit" class="text-primary" :owner-name="payment.raw_data[3] || ''" />
          </div>
        </td>
      </tr>
      <tr>
        <td>Сумма</td>
        <td>
          <ShowPrice :price="payment.price" />
        </td>
      </tr>
      <tr>
        <td>Назначение</td>
        <td style="padding: 0px;">
          <q-expansion-item
            :label="payment.raw_data[4]"
          >
            <div class="bg-grey-2 q-pa-sm">
              <pre>{{ payment.raw_data }}           </pre>
            </div>
          </q-expansion-item>
        </td>
      </tr>
      <tr :class="{'bg-red-1': typeError}">
        <td>Тип платежа</td>
        <td style="padding: 0 16px">
          <div v-if="editRateGroup" class="row items-center">
            <RateGroupSelect
              v-model="rateGroupId"
              outlined
              dense
              class="filter-item"
            />
            <q-space />
            <div>
              <q-btn label="Ok" color="primary" @click="changeRateGroup" />
            </div>
          </div>
          <div v-else class="row items-center">
            <div>
              {{ payment.rate?.name }}
            </div>
            <q-space />
            <div v-if="edit">
              <q-btn icon="edit" flat color="primary" dense size="sm" @click="editRateGroup = !editRateGroup" />
            </div>
          </div>
        </td>
      </tr>
      <TrReading
        v-if="payment.rate.depends === 2"
        :payment="payment"
        :edit="edit"
        @reloadData="reloadData"
      />
      <tr>
        <td>Примечание</td>
        <td>
          <div v-if="editDescription" class="row items-center">
            <div>
              <q-input
                v-model="description"
                outlined
                autogrow
                dense
              />

            </div>
            <q-space />
            <div>
              <q-btn label="Ok" color="primary" @click="saveDescription" />
            </div>
          </div>
          <div v-else class="row items-center">
            <div v-html="descriptionHtml" />
            <q-space />
            <div v-if="edit">
              <q-btn icon="edit" flat color="primary" dense size="sm" @click="editDescription = !editDescription" />
            </div>
          </div>
        </td>
      </tr>
      </tbody>
    </q-markup-table>
    <div v-if="edit && payment.error" class="row items-center q-pt-sm">
      <div class="q-pa-sm">
        <q-btn label="Подтвердить данные" icon="done" color="secondary" @click="deleteError" />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'
import FindOwnerPopup from 'src/Modules/Owner/components/FindOwnerPopup/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import { updatePayment } from 'src/Modules/Bookkeeping/api/paymentApi'
import { successMessage } from 'src/utils/message'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import { useQuasar } from 'quasar'
import PaymentDeleteBtn from 'src/Modules/Bookkeeping/components/Payment/PaymentDeleteBtn/index.vue'
import TrReading from './components/TrReading/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    PaymentDeleteBtn,
    SteadSelect,
    ShowPrice,
    FindOwnerPopup,
    RateGroupSelect,
    TrReading
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
    const $q = useQuasar()
    const findSteadShow = ref(false)
    const editRateGroup = ref(false)
    const editDescription = ref(false)
    const steadId = ref('')
    const rateGroupId = ref('')
    const description = ref('')
    const steadError = computed(() => {
      return props.payment.stead?.number !== props.payment?.raw_data[2]
    })
    const typeError = computed(() => {
      return !props.payment.rate
    })

    const changeStead = () => {
      const data = {
        stead_id: steadId.value
      }
      saveData(data)
      findSteadShow.value = false
    }
    const saveDescription = () => {
      const data = {
        description: description.value
      }
      saveData(data)
      editDescription.value = false
    }
    const deletePayment = () => {
      emit('deletePayment')
    }
    const deleteError = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Установить что что все данные в платеже проверены?',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Сохранить',
          color: 'primary'
        },
        persistent: true
      }).onOk(() => {
        const data = {
          data_verified: true
        }
        saveData(data)
      })
    }
    const descriptionHtml = computed(() => {
      return description.value.replace(/\n/g, '<br />')
    })
    const changeRateGroup = () => {
      const data = {
        rate_group_id: rateGroupId.value
      }
      saveData(data)
      editRateGroup.value = false
    }
    const reloadData = () => {
      emit('reload')
    }
    const saveData = (data) => {
      updatePayment(props.payment.id, data)
        .then(res => {
          successMessage('ok')
        })
        .finally(() => {
          emit('reload')
        })
    }
    onMounted(() => {
      steadId.value = props.payment.stead_id
      rateGroupId.value = props.payment.rate_group_id
      description.value = props.payment.description || ''
    })
    return {
      changeStead,
      deletePayment,
      deleteError,
      reloadData,
      changeRateGroup,
      saveDescription,
      editRateGroup,
      description,
      editDescription,
      rateGroupId,
      steadId,
      descriptionHtml,
      typeError,
      steadError,
      findSteadShow
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
