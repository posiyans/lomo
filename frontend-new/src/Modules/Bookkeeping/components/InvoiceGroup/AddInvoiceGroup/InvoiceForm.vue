<template>
  <div class="q-gutter-sm">
    <div>
      <q-select
        v-model="invoiceGroup.stead_type"
        :options="[
          {
            label: 'Всем участкам',
            value: 'all',
            }
          ]"
        map-options
        emit-value
        outlined
        dense
      />
    </div>
    <div class="row items-center">
      <div class="col-grow q-pr-sm">
        <q-select
          v-model="invoiceGroup.rate_group_id"
          :options="rateGroupList"
          label="Тип платежа"
          outlined
          dense
          map-options
          emit-value
          transition-show="jump-up"
          transition-hide="jump-up"
          option-label="name"
          option-value="id"
          @update:model-value="getRateData"
        />
      </div>

      <div style="padding-top: 4px">
        <div class="row items-center q-col-gutter-xs br-05 ba b--gray text-grey-8 ">
          <ShowTime :time="invoiceDate" before="на дату" format="DD-MM-YYYY" />
          <q-btn icon="event" flat round color="grey" size="12.5px">
            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
              <q-date
                v-model="invoiceDate"
                mask="YYYY-MM-DD"
                yearsInMonthView
                flat
                @update:model-value="getRateData"
              >
                <div class="row items-center justify-end q-gutter-sm">
                  <q-btn label="OK" color="primary" flat v-close-popup />
                </div>
              </q-date>
            </q-popup-proxy>
          </q-btn>
        </div>
      </div>
    </div>
    <div class="q-px-sm">
      {{ rateDescription }}
    </div>
    <div v-if="currentRateGroup?.depends === 2" class="text-primary q-px-sm ">
      Выставить счета на показания приборов переданные в указанный периуд
    </div>
    <div>
      <q-input v-model="invoiceGroup.title" outlined dense />
    </div>
    <RateBlock :rate-type="rateType" />

    <div>
      <q-btn label="Начислить" color="primary" :loading="loading" :disable="saveDisable" @click="saveInvoice" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref, watch } from 'vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import RateBlock from './components/RateBlock/index.vue'
import { getRateGroupList, getRateListForGroup } from 'src/Modules/Rate/api/rateAdminApi'
import { date, useQuasar } from 'quasar'
import { addInvoiceGroup } from 'src/Modules/Bookkeeping/api/invoiceGroupApi'
import ShowTime from 'components/ShowTime/index.vue'

export default defineComponent({
  components: {
    RateGroupSelect,
    RateBlock,
    ShowTime
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const $q = useQuasar()
    const loading = ref(false)
    const rateType = ref([])
    const invoiceDate = ref(date.formatDate(new Date(), 'YYYY-MM-DD'))
    const saveDisable = computed(() => {
      return invoiceGroup.value.stead_type === '' || invoiceGroup.value.rate_group_id === '' || !rateType.value.find(item => item.selected)
    })
    const invoiceGroup = ref({
      stead_type: 'all',
      rate_group_id: '',
      title: '',
    })
    const currentRateGroup = computed(() => {
      return rateGroupList.value.find(item => item.id === invoiceGroup.value.rate_group_id)
    })
    const getRateData = () => {
      if (invoiceGroup.value.rate_group_id) {
        const params = {
          for_date: invoiceDate.value
        }
        getRateListForGroup(invoiceGroup.value.rate_group_id, params)
          .then(res => {
            rateType.value = res.data.data
            rateType.value.forEach(item => {
              item.selected = true
            })
          })
      } else {
        rateType.value = []
      }
    }
    const rateGroupList = ref([])
    const getRateGroupData = () => {
      getRateGroupList()
        .then(response => {
          rateGroupList.value = response.data.data
          if (rateGroupList.value.length === 1) {
            invoiceGroup.value.rate_group_id = rateGroupList.value[0].id
          }
        })
    }
    onMounted(() => {
      getRateGroupData()
    })
    const renderName = () => {
      if (currentRateGroup.value) {
        let title = currentRateGroup.value.name
        if (currentRateGroup.value.payment_period === 1) {
          title += ' ' + date.formatDate(invoiceDate.value, 'MM-YYYY')
        } else if (currentRateGroup.value.payment_period === 12) {
          title += ' ' + date.formatDate(invoiceDate.value, 'YYYY')
        } else {
          title += ' ' + date.formatDate(invoiceDate.value, 'MM-YYYY')
        }
        rateType.value.forEach(item => {
          if (item.selected) {
            title += ', ' + item.name
          }
        })
        invoiceGroup.value.title = title
      } else {
        invoiceGroup.value.title = ''
      }
    }
    const rateDescription = computed(() => {
      if (currentRateGroup.value) {
        let text = currentRateGroup.value.name
        if (currentRateGroup.value.payment_period === 12) {
          text += ' за период с '
          text += date.formatDate(date.startOfDate(invoiceDate.value, 'year'), 'DD-MM-YYYY')
          text += ' по ' + date.formatDate(date.endOfDate(invoiceDate.value, 'year'), 'DD-MM-YYYY')
        } else if (currentRateGroup.value.payment_period === 1) {
          text += ' за период с '
          text += date.formatDate(date.startOfDate(invoiceDate.value, 'month'), 'DD-MM-YYYY')
          text += ' по ' + date.formatDate(date.endOfDate(invoiceDate.value, 'month'), 'DD-MM-YYYY')
        }
        return text
      }
      return ''
    })

    const sendData = () => {
      const data = {
        rate_group_id: invoiceGroup.value.rate_group_id,
        invoice_date: invoiceDate.value,
        stead_type: invoiceGroup.value.stead_type,
        title: invoiceGroup.value.title,
        rate: rateType.value.filter(item => item.selected)
      }
      console.log(data)
      loading.value = true
      addInvoiceGroup(data)
        .then(res => {
          $q.dialog({
            title: 'Успех',
            message: 'Кол-во созданных счетов: ' + res.data.data.total + ' на сумму ' + res.data.data.price + ' руб.',
            ok: {
              noCaps: true,
              outline: true,
              label: 'Ок',
              color: 'primary'
            },
            persistent: true
          })
            .onOk(() => {
              emit('success')
            })
        })
        .catch(er => {
          $q.dialog({
            title: 'Ошибка',
            message: er.response.data.errors,
            icon: 'info',
            ok: {
              noCaps: true,
              label: 'Ок',
              color: 'negative'
            },
            persistent: true
          })
        })
        .finally(() => {
          loading.value = false
        })
    }
    const saveInvoice = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Выставить счета на ' + invoiceGroup.value.title + ' ?',
        cancel: {
          noCaps: true,
          outline: true,
          flat: true,
          label: 'Отмена',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Да',
          color: 'primary'
        },
        persistent: true
      })
        .onOk(() => {
          sendData()
        })


    }

    watch(() => [invoiceGroup.value.rate_group_id, invoiceDate.value],
      () => {
        renderName()
      })
    watch(() => rateType.value,
      () => {
        renderName()
      },
      { deep: true }
    )
    return {
      data,
      rateDescription,
      rateGroupList,
      invoiceGroup,
      getRateData,
      loading,
      saveDisable,
      saveInvoice,
      invoiceDate,
      currentRateGroup,
      rateType
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
