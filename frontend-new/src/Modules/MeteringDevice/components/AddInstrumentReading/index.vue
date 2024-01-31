<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn icon="add" flat color="primary" />
      </slot>
    </div>
    <q-dialog v-model="dialogVisible">
      <q-card style="min-width: 450px;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Добавить показания</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form
            @submit="onSubmit"
            class="q-gutter-md"
          >
            <div>
              <MeteringDeviceForSteadSelect
                v-model="reading.device_id"
                :stead-id="steadId"
                outlined
                dense
                :rules="[required]"
              />
            </div>
            <div>
              <q-input
                v-model="reading.value"
                outlined
                dense
                label="Показания"
                :rules="[required]"
                @update:model-value="setValue"
              />
            </div>
            <div>
              <InputDate
                v-model="reading.date"
                outlined
                dense
                label="От какой даты"
                :rules="[required]"
              />
            </div>
            <div class="text-right">
              <q-btn label="Добавить" color="primary" type="submit" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import MeteringDeviceForSteadSelect from 'src/Modules/MeteringDevice/components/MeteringDeviceForSteadSelect/index.vue'
import InputDate from 'components/Input/InputDate/index.vue'
import { required } from 'src/utils/validators.js'
import { addInstrumentReading } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'
import { errorMessage } from 'src/utils/message'
import { date, useQuasar } from 'quasar'

export default defineComponent({
  components: {
    MeteringDeviceForSteadSelect,
    InputDate
  },
  props: {
    steadId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    let timer = null
    const reading = ref({
      stead_id: props.steadId,
      device_id: '',
      value: '',
      date: date.formatDate(new Date(), 'YYYY-MM-DD'),
    })
    const dialogVisible = ref(false)
    const showDialog = () => {
      reading.value.value = ''
      reading.value.device_id = ''
      dialogVisible.value = true
    }
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
        }
      }, 500)
    }
    const $q = useQuasar()
    const onSubmit = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Добавить показания?',
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
        addInstrumentReading(reading.value)
          .then(res => {
            dialogVisible.value = false
            emit('success')
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }
    return {
      reading,
      setValue,
      onSubmit,
      dialogVisible,
      required,
      showDialog
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
