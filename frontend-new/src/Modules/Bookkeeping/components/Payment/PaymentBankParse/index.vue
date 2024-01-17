<template>
  <div class="relative-position">
    <div>
      <div class="row items-center q-col-gutter-md justify-between">
        <input
          ref="btnRefd"
          type="file"
          class="hidden"
          multiple
          :accept="acceptFileType"
          @change="change"
        />
        <div @click.stop="showDialog">
          <q-btn color="primary" no-caps label="Добавить выписку из банка" />
          <div class="text-small-85 text-grey">
            txt,cvs,xlsx выписки из банка, до 100 файлов
          </div>
        </div>
        <div>
          <q-checkbox v-model="onlyError" label="Показать только с ошибками" />
        </div>
      </div>
    </div>
    <div>
      <ShowTable :list="filtredPaymentData" :edit="edit" @reload="reload" />
    </div>
    <div v-if="paymentErrors > 0" class="fixed-bottom-right bg-red text-white q-px-md cursor-pointer" @click="onlyError = true">
      {{ paymentErrors }}
      <DecOfNum :number="paymentErrors" :titles="['платеж', 'платежа', 'платежей']" />
      требуют уточнения
    </div>

    <div v-if="uploadingData" class="fixed-bottom-right" style="bottom: 1.25em">
      <div class="bg-grey q-px-md text-white">
        Обрабатывается {{ paymentData.length - poolPayment.length }} / {{ paymentData.length }} платеж {{ uploadingData?.raw_data[1] }} руб. от {{ uploadingData?.raw_data[0] }}
      </div>
    </div>
    <div>
      <q-btn color="negative" flat label="Отмена" @click="cancel" />
      <q-btn color="primary" :loading="loading" label="Загрузить" @click="uploadData" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { uid, useQuasar } from 'quasar'
import readFile from './js/readFile'
import ShowTable from './components/ShowTable/index.vue'
import { addPayment, getPayment } from 'src/Modules/Bookkeeping/api/paymentApi'
import DecOfNum from 'components/DecOfNum/index.vue'

export default defineComponent({
  components: {
    ShowTable,
    DecOfNum
  },
  props: {},
  setup(props, { emit }) {
    const loading = ref(false)
    const onlyError = ref(false)
    const edit = ref(false)
    const btnRefd = ref(null)
    const poolPayment = ref([])
    const paymentData = ref([])
    const filtredPaymentData = computed(() => {
      if (onlyError.value) {
        return paymentData.value.filter(item => item.error)
      }
      return paymentData.value
    })
    const uploadingData = ref(null)
    const paymentErrors = computed(() => {
      return paymentData.value.reduce((accumulator, currentValue) => {
        if (currentValue.error) {
          accumulator++
        }
        return accumulator
      }, 0)
    })
    const $q = useQuasar()
    const showDialog = () => {
      btnRefd.value.click()
    }
    const acceptFileType = '.txt,.csv,.xls,.xlsx'
    const cancel = () => {
      paymentData.value = []
      loading.value = false
    }
    const uploadData = () => {
      edit.value = true
      loading.value = true
      paymentErrors.value = 0
      poolPayment.value = paymentData.value.map(item => item.uid)
      sendPayment()
    }
    const sendPayment = () => {
      if (poolPayment.value.length > 0) {
        const payUid = poolPayment.value.shift()
        const data = paymentData.value.find(item => item.uid === payUid)
        uploadingData.value = data
        if (!data.done) {
          data.upload = true
          const tmp = {
            raw: data.raw_data
          }
          addPayment(tmp)
            .then(res => {
              data.id = res.data.data.id
              data.stead = res.data.data.stead
              data.stead_id = res.data.data.stead_id
              data.rate_group_id = res.data.data.rate_group_id
              data.rate = res.data.data.rate
              data.price = res.data.data.price
              data.error = res.data.data.error
              data.description = res.data.data.description
              data.duplicate = res.data.data.duplicate || false
              data.done = true
              data.uploadError = false
              // if (data.error) {
              //   paymentErrors.value++
              // }
            })
            .catch(() => {
              data.uploadError = true
            })
            .finally(() => {
              data.upload = false
              sendPayment()
            })
        } else {
          sendPayment()
        }
      } else {
        uploadingData.value = null
        loading.value = false
      }
    }
    const reload = (uid) => {
      const data = paymentData.value.find(item => item.uid === uid)
      getPayment(data.id)
        .then(res => {
          data.id = res.data.data.id
          data.stead = res.data.data.stead
          data.stead_id = res.data.data.stead_id
          data.rate_group_id = res.data.data.rate_group_id
          data.rate = res.data.data.rate
          data.price = res.data.data.price
          data.error = res.data.data.error
          data.description = res.data.data.description
          data.done = true
        })
        .finally(() => {
          data.upload = false
        })
    }
    const change = () => {
      const tmp = []
      if (btnRefd.value.files) {
        [...btnRefd.value.files].forEach(item => {
          readFile(item).then(text => {
            text.forEach(i => {
              paymentData.value.push({
                raw_data: i,
                done: false,
                uid: uid(),
                error: false,
                uploadError: false,
                upload: false,
              })
            })
          })
        })
      }
    }
    return {
      filtredPaymentData,
      showDialog,
      cancel,
      onlyError,
      reload,
      edit,
      paymentErrors,
      poolPayment,
      uploadingData,
      loading,
      acceptFileType,
      paymentData,
      change,
      uploadData,
      btnRefd
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
