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
      <div style="min-width: 3em;">
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

export default defineComponent({
  components: {},
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
    const status = ref('old')
    const oldValue = ref('')
    const reading = ref({
      value: '',
      device_id: props.device.id,
      payment_id: props.payment.id,
      date: props.payment.payment_date.split(' ')[0]
    })
    onMounted(() => {
      props.payment.readings.forEach(item => {
        if (item.metering_device_id === props.device.id) {
          reading.value.value = item.value
        }
      })
      oldValue.value = reading.value.value
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
      // $q.dialog({
      //   title: 'Подтвердите',
      //   message: 'Добавить показания?',
      //   cancel: {
      //     noCaps: true,
      //     flat: true,
      //     label: 'Отмена',
      //     color: 'negative'
      //   },
      //   ok: {
      //     noCaps: true,
      //     outline: true,
      //     label: 'Добавить',
      //     color: 'primary'
      //   },
      //   persistent: true
      // }).onOk(() => {
      addInstrumentReading(reading.value)
        .then(res => {
          emit('success')
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
    return {
      reading,
      status,
      setValue,
      required,
      onSubmit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
