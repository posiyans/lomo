<template>
  <q-form
    @submit="onSubmit"
  >
    <div class="row items-center no-wrap justify-between">
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
          v-model="reading.value"
          dense
          outlined
          :readonly="status === 'loading'"
          label="Показание"
          lazy-rules
          @update:model-value="setValue"
        >
          <template v-slot:append>
            <div class="text-black text-small-65">
              {{ device.rate.unit_name }}
            </div>
          </template>
        </q-input>

      </div>
      <DeleteInstrumentReading v-if="readingId && status === 'old'" :key="key" :reading-id="readingId" @success="reloadData">
        <template v-slot:default="{ loading }">
          <q-btn flat :loading="loading" padding="sm" icon="delete" color="negative" />
        </template>
      </DeleteInstrumentReading>
      <div v-else style="min-width: 3em;">
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
import { defineComponent, onMounted, ref } from 'vue'
import { useQuasar } from 'quasar'
import { required } from 'src/utils/validators.js'
import { addInstrumentReading } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'
import DeleteInstrumentReading from 'src/Modules/MeteringDevice/components/DeleteInstrumentReading/index.vue'

export default defineComponent({
  components: {
    DeleteInstrumentReading
  },
  props: {
    device: {
      type: Object,
      required: true
    },
    payment: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const key = ref(1)
    const status = ref('old')
    const readingId = ref(null)
    const oldValue = ref('')
    const reading = ref({
      value: ''
    })
    const init = () => {
      props.payment.readings.forEach(item => {
        console.log(props.payment.readings)
        if (item.metering_device_id === props.device.id) {
          readingId.value = item.id
          reading.value.value = item.value
        }
      })
      oldValue.value = reading.value.value
    }
    onMounted(() => {
      init();
    })
    let timer = null
    const setValue = () => {
      if (timer) clearTimeout(timer)
      timer = setTimeout(() => {
        if (reading.value.value) {
          const tmp = reading.value.value
          if (!isNaN(tmp)) {
            reading.value.value = tmp
          } else {
            reading.value.value = ''
          }
          if (oldValue.value !== reading.value.value) {
            status.value = 'done'
          }
        }
      }, 500)
    }
    const $q = useQuasar()
    const onSubmit = () => {
      status.value = 'loading'
      const data = {
        payment_id: props.payment.id,
        devices: [
          {
            device_id: props.device.id,
            value: reading.value.value,
          }
        ],
        date: props.payment.payment_date.split(' ')[0]
      }
      addInstrumentReading(data)
        .then(res => {
          emit('success')
          init()
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
        .finally(() => {
          status.value = 'ok'
          setTimeout(() => {
            status.value = ''
          }, 3000)
        })
      // })
    }
    const reloadData = () => {
      status.value = 'old'
      key.value++
      emit('success')
      init()
    }
    return {
      reloadData,
      key,
      reading,
      status,
      readingId,
      setValue,
      required,
      onSubmit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
