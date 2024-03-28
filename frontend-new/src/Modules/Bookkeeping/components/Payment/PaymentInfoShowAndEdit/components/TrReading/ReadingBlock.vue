<template>
  <q-form
    @submit="onSubmit"
  >
    <div class="row items-center no-wrap q-col-gutter-sm">
      <div>
        <div>
          {{ device.rate.name }}
        </div>
        <div class="text-small-65">
          Sn: {{ device.serial_number }}
        </div>
      </div>
      <div>
        <q-input
          v-model.number="reading"
          dense
          outlined
          :readonly="status === 'loading'"
          label="Показание"
          @update:model-value="setValue"
        >
          <template v-slot:append>
            <div class="text-black text-small-65">
              {{ device.rate.unit_name }}
            </div>
          </template>
        </q-input>

      </div>
      <DeleteInstrumentReading v-if="readingId && status === 'old'" :reading-id="readingId" @success="deleteReading">
        <template v-slot:default="{ loading }">
          <q-btn flat :loading="loading" padding="sm" icon="delete" color="negative" />
        </template>
      </DeleteInstrumentReading>
      <div v-else style="min-width: 3em;">
        <q-btn v-if="status === 'done'" flat padding="sm" icon="restore_page" color="red" @click="cancelChange" />
        <q-btn v-if="status === 'done'" flat padding="sm" icon="save" color="green" type="submit" />
        <q-spinner
          v-if="status === 'loading'"
          color="primary"
          size="3em"
        />
        <q-btn v-if="status === 'ok'" flat padding="sm" icon="done" color="green" />
      </div>
    </div>
  </q-form>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { addInstrumentReading } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'
import DeleteInstrumentReading from 'src/Modules/MeteringDevice/components/DeleteInstrumentReading/index.vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'

export default defineComponent({
  components: {
    DeleteInstrumentReading
  },
  props: {
    device: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const { payment, getPaymentData, loading, updatePaymentData } = useCurrentPayment()
    const status = ref('old')
    const oldValue = ref('')
    const reading = ref('')
    const readingId = computed(() => {
      let id = null
      payment.value.readings.forEach(item => {
        if (item.metering_device_id === props.device.id) {
          id = item.id
        }
      })
      return id
    })
    const init = () => {
      payment.value.readings.forEach(item => {
        if (item.metering_device_id === props.device.id) {
          reading.value = item.value
          status.value = 'old'
        }
      })
      oldValue.value = reading.value
    }
    onMounted(() => {
      init();
    })
    let timer = null
    const setValue = () => {
      if (timer) clearTimeout(timer)
      timer = setTimeout(() => {
        if (reading.value) {
          const tmp = reading.value
          if (!isNaN(tmp)) {
            reading.value = tmp
          } else {
            reading.value = ''
          }
          if (oldValue.value !== reading.value) {
            status.value = 'done'
          }
        }
      }, 500)
    }
    const onSubmit = () => {
      status.value = 'loading'
      loading.value = true
      const data = {
        payment_id: payment.value.id,
        devices: [
          {
            device_id: props.device.id,
            value: reading.value,
          }
        ],
        date: payment.value.payment_date.split(' ')[0]
      }
      addInstrumentReading(data)
        .then(async res => {
          await getPaymentData()
          status.value = 'ok'
          setTimeout(() => {
            init()
          }, 3000)
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
          status.value = ''
        })
        .finally(() => {
          loading.value = false
        })
    }

    const deleteReading = async () => {
      reading.value = ''
      await getPaymentData()
      init()
    }
    const cancelChange = () => {
      reading.value = oldValue.value
      status.value = 'old'
    }
    return {
      deleteReading,
      cancelChange,
      reading,
      status,
      readingId,
      setValue,
      onSubmit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
