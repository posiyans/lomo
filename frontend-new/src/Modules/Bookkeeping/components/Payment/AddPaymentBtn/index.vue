<template>
  <div>
    <div @click="clickAction">
      <slot>
        <q-btn icon="add" round flat color="negative">
          <q-tooltip>
            Добавить платеж
          </q-tooltip>
        </q-btn>
      </slot>
    </div>
    <q-dialog
      v-model="dialogVisible"
      maximized
    >

      <q-card>
        <q-form
          @submit="onSubmit"
        >
          <q-card-section>
            <div class="row items-center q-pb-none">
              <div class="text-h6 text-teal-10">Добавить платеж</div>
              <q-space />
              <q-btn icon="close" flat round dense v-close-popup />
            </div>
            <div class="row items-center q-col-gutter-sm">
              <div>
                Участок №
              </div>
              <div class="text-weight-bold text-primary">
                {{ stead.number }}
              </div>
              <div>
                размер
              </div>
              <div class="text-weight-bold text-primary">
                {{ stead.size }}
              </div>
              <div>
                мм <sup>2</sup>
              </div>
            </div>
          </q-card-section>

          <q-card-section class="q-gutter-sm">
            <div>
              <RateGroupSelect
                v-model="payment.rate_group_id"
                outlined
                :rules="[required]"
                @update:model-value="changeRateGroup"

              />
            </div>
            <div>
              <q-input
                v-model.number="payment.b"
                label="Сумм платежа"
                outlined
                :rules="[
                  val => val && val > 0 || 'Сумма должна быть больше нуля'
                ]"
              />
            </div>
            <div>
              <InputDate
                v-model="payment.date"
                outlined
                label="Дата платежа"
                :rules="[required]"
              />
            </div>
            <div>
              <q-input
                v-model.trim="payment.title"
                label="Назначение платежа"
                outlined
                clearable
                :rules="[required]"
              >
                <template v-slot:append>
                  <q-btn icon="autorenew" flat @click="payment.title = rate.name" />
                </template>
              </q-input>
            </div>
            <div v-if="rate.depends === 2" style="max-width: 600px;" class="q-pa-sm">
              <div>
                Добавить показяния приборов
              </div>
              <div class="full-width">
                <AddInstrumentReadingForm :instrument-reading="instrumentReading" />
              </div>
            </div>
            <div>
              <q-input
                v-model.trim="payment.description"
                label="Подробнее"
                outlined
                autogrow
                clearable
              >
              </q-input>
            </div>
            <ShowPrice
              before-text="Добавить платеж на сумму "
              :price="sum"
              class="text-teal-10"
            />
          </q-card-section>

          <q-card-actions align="right" class="q-gutter-sm">
            <div>
              <q-btn label="Отмена" flat color="negative" v-close-popup />
              <q-btn label="Добавить" color="primary" :disable="payment.b === 0" type="submit" />
            </div>
          </q-card-actions>
        </q-form>
      </q-card>

    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useQuasar } from 'quasar'
import DecOfNum from 'components/DecOfNum/index.vue'
import InputNumberFloat from 'src/components/Input/InputNumberFloat/index.vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import { required } from 'src/utils/validators'
import ShowPrice from 'src/components/ShowPrice/index.vue'
import { useRateStore } from 'src/Modules/Rate/stores/useRateStore'
import { errorMessage } from 'src/utils/message'
import InputDate from 'components/Input/InputDate/index.vue'
import AddInstrumentReadingForm from 'src/Modules/MeteringDevice/components/AddInstrumentReading/Form.vue'
import { useInstrumentReading } from 'src/Modules/MeteringDevice/components/AddInstrumentReading/useInstrumentReading'
import { useSteadInfo } from 'src/Modules/Stead/hooks/useSteadInfo'
import { addPayment } from 'src/Modules/Bookkeeping/api/paymentApi'

export default defineComponent({
  components: {
    DecOfNum,
    InputNumberFloat,
    RateGroupSelect,
    ShowPrice,
    InputDate,
    AddInstrumentReadingForm
  },
  props: {
    steadId: {
      type: [String, Number],
      required: true
    }
  },
  setup: function (props, { emit }) {
    const instrumentReading = useInstrumentReading()
    const changeRateGroup = () => {
      if (rate.value.depends === 2) {
        instrumentReading.getData(props.steadId)
      }
    }
    const rateStore = useRateStore()
    onMounted(() => {
      rateStore.getData()
    })
    const rate = computed(() => {
      return rateStore.rateGroupInfo(payment.value.rate_group_id)
    })
    const dialogVisible = ref(false)
    const payment = ref({
      b: 0,
      date: '',
      title: '',
      description: '',
      rate_group_id: ''
    })

    const sum = computed(() => {
      const tmp = +payment.value.b
      return tmp?.toFixed(2)
    })
    const { stead, getData } = useSteadInfo()
    const $q = useQuasar()
    const clickAction = () => {
      getData(props.steadId)
      payment.value.b = 0
      payment.value.title = ''
      payment.value.date = ''
      payment.value.description = ''
      dialogVisible.value = true
    }
    const onSubmit = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Добавить платеж участка № ' + stead.value.number + ' на сумму ' + payment.value.b + ' руб ?',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Добавить',
          color: 'primary'
        },
        persistent: true
      }).onOk(() => {
        const data = {
          raw: [payment.value.date, payment.value.b, stead.value.number, '', payment.value.title],
          stead_id: props.steadId,
          title: payment.value.title,
          description: payment.value.description,
          price: payment.value.b,
          rate_group_id: payment.value.rate_group_id,
          date: payment.value.date
        }
        addPayment(data)
          .then(res => {
            if (res.data.data.duplicate) {
              errorMessage('Данный платеж уже есть в системе')
            }
            emit('success')
            dialogVisible.value = false
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }

    return {
      onSubmit,
      changeRateGroup,
      stead,
      clickAction,
      dialogVisible,
      payment,
      sum,
      required,
      rate,
      instrumentReading
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
