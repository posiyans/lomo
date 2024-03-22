<template>
  <div>
    <div @click="clickAction">
      <slot>
        <q-btn icon="add" round flat color="negative">
          <q-tooltip>
            Добавить счет
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
              <div class="text-h6">Добавить счет</div>
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
                v-model="invoice.rate_group_id"
                outlined
                :rules="[required]"
              />
            </div>
            <div>
              <q-input
                v-model.number="invoice.b"
                label="Сумма"
                outlined
                :rules="[
                  val => val && val > 0 || 'Сумма должна быть больше нуля'
                ]"
              >
                <template v-slot:append>
                  <q-btn icon="dashboard" color="primary" flat>
                    <q-popup-edit v-model="invoice.a" v-slot="scope" @update:model-value="setSumForStead">
                      <q-input type="number" v-model.number="scope.value" dense label="Сумма с сотки" autofocus @keyup.enter="scope.set" />
                    </q-popup-edit>
                  </q-btn>
                </template>
              </q-input>
            </div>
            <div>
              <q-input
                v-model.trim="invoice.title"
                label="Назначение платежа"
                outlined
                clearable
                :rules="[required]"
              >
                <template v-slot:append>
                  <q-btn icon="autorenew" flat @click="renewTitle" />
                </template>
              </q-input>
            </div>
            <div>
              <q-input
                v-model.trim="invoice.description"
                label="Подробнее"
                outlined
                autogrow
                clearable
              >
              </q-input>
            </div>
            <ShowPrice
              before-text="Добавить счет на сумму "
              :price="sum"
            />
          </q-card-section>

          <q-card-actions align="right" class="q-gutter-sm">
            <div>
              <q-btn label="Отмена" flat color="negative" v-close-popup />
              <q-btn label="Добавить" color="primary" :disable="invoice.b === 0" type="submit" />
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
import { getSteadInfo } from 'src/Modules/Stead/api/stead'
import DecOfNum from 'components/DecOfNum/index.vue'
import InputNumberFloat from 'src/components/Input/InputNumberFloat/index.vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import { required } from 'src/utils/validators'
import ShowPrice from 'src/components/ShowPrice/index.vue'
import { useRateStore } from 'src/Modules/Rate/stores/useRateStore'
import { addInvoice } from 'src/Modules/Bookkeeping/api/invoiceAdminApi'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {
    DecOfNum,
    InputNumberFloat,
    RateGroupSelect,
    ShowPrice
  },
  props: {
    steadId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const rateStore = useRateStore()
    onMounted(() => {
      rateStore.getData()
    })
    const rate = computed(() => {
      return rateStore.rateGroupInfo(invoice.value.rate_group_id)
    })
    const dialogVisible = ref(false)
    const invoice = ref({
      a: '',
      b: 0,
      title: '',
      description: '',
      rate_group_id: ''
    })
    const sumForSize = computed(() => {
      if (invoice.value.a > 0) {
        const tmp = +invoice.value.a * (+stead.value.size / 100)
        return invoice.value.a + ' руб с сотки * ' + stead.value.size / 100 + ' = ' + tmp?.toFixed(2) + ' руб'
      }
      return ''
    })
    const sum = computed(() => {
      const tmp = +invoice.value.b
      return tmp?.toFixed(2)
    })
    const steads = ref({})
    const stead = computed(() => {
      return steads.value[props.steadId] ?? {}
    })
    const getData = () => {
      const data = {
        id: props.steadId
      }
      getSteadInfo(data)
        .then(res => {
          steads.value[props.steadId] = res.data.data
        })
    }
    const setSumForStead = () => {
      invoice.value.b = invoice.value.a * stead.value.size / 100
      if (!invoice.value.title) {
        renewTitle()
      }
    }
    const $q = useQuasar()
    const clickAction = () => {
      getData()
      invoice.value.a = ''
      invoice.value.b = 0
      invoice.value.title = ''
      invoice.value.description = ''
      dialogVisible.value = true
    }
    const onSubmit = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Добавить счет участку ' + stead.value.number + ' на сумму ' + invoice.value.b + ' руб ?',
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
          stead_id: props.steadId,
          title: invoice.value.title,
          description: invoice.value.description,
          price: invoice.value.b,
          rate_group_id: invoice.value.rate_group_id
        }
        addInvoice(data)
          .then(() => {
            emit('success')
            dialogVisible.value = false
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }
    const renewTitle = () => {
      let description = ''
      if (rate.value) {
        description += rate.value.name
      }
      if (sumForSize.value) {
        if (description) {
          description += ' '
        }
        description += sumForSize.value
      }
      invoice.value.title = description
    }
    return {
      onSubmit,
      stead,
      clickAction,
      dialogVisible,
      invoice,
      sum,
      renewTitle,
      sumForSize,
      setSumForStead,
      required,
      rate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
