<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn icon="add" flat color="primary">
          <q-tooltip>
            Добавить показяния приборов
          </q-tooltip>
        </q-btn>
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
            <div v-if="!steadId">
              <SteadSelect
                v-model="currentSteadId"
                outlined
                dense
                @update:model-value="changeStead"
              />
            </div>
            <div v-if="loading"
                 class="text-center"
                 style="min-height: 90px;"
            >
              <q-spinner
                color="primary"
                size="10em"
              />
            </div>
            <div
              v-else
              class="q-gutter-sm"
              style="min-height: 90px;"
            >
              <div
                v-for="item in allDevice"
                :key="item.id"
              >
                <EditReadingItem
                  :device="item"
                  @setValue="setValue"
                />
              </div>
            </div>
            <div v-if="showDateInput">
              <InputDate
                v-model="currentDate"
                outlined
                dense
                label="От какой даты"
                :rules="[required]"
              />
            </div>
            <div class="text-right">
              <q-btn label="Добавить" color="primary" :disable="disableBtn" type="submit" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import MeteringDeviceForSteadSelect from 'src/Modules/MeteringDevice/components/MeteringDeviceForSteadSelect/index.vue'
import InputDate from 'components/Input/InputDate/index.vue'
import { addInstrumentReading } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'
import { errorMessage, successMessage } from 'src/utils/message'
import { date, useQuasar } from 'quasar'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'
import EditReadingItem from './components/EditReadingItem/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { required } from 'src/utils/validators.js'

export default defineComponent({
  components: {
    MeteringDeviceForSteadSelect,
    InputDate,
    SteadSelect,
    EditReadingItem
  },
  props: {
    steadId: {
      type: [String, Number],
      default: ''
    }
  },
  setup(props, { emit }) {
    const disableBtn = computed(() => {
      return Object.values(devices.value).length === 0
    })
    const authStore = useAuthStore()
    const showDateInput = computed(() => {
      return authStore.checkPermission('reading-edit')
    })
    const devices = ref({})
    const currentDate = ref(date.formatDate(new Date(), 'YYYY-MM-DD'))
    const currentSteadId = ref(props.steadId)
    const dialogVisible = ref(false)
    const showDialog = () => {
      currentSteadId.value = props.steadId
      if (currentSteadId.value) {
        getData()
      }
      devices.value = {}
      dialogVisible.value = true
    }
    const setValue = (val) => {
      delete devices.value[val.device_id]
      if (val.value) {
        devices.value[val.device_id] = val
      }
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
        const data = {
          date: currentDate.value,
          devices: Object.values(devices.value)
        }
        addInstrumentReading(data)
          .then(res => {
            dialogVisible.value = false
            successMessage('Данные успешно добавлены')
            emit('success')
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }
    const changeStead = () => {
      getData()
    }
    const allDevice = ref([])
    const loading = ref(false)
    const getData = () => {
      loading.value = true
      getMeteringDeviceForStead(currentSteadId.value)
        .then(res => {
          allDevice.value = res.data.data
          devices.value = {}
        })
        .finally(() => {
          loading.value = false
        })
    }

    return {
      currentDate,
      showDateInput,
      currentSteadId,
      disableBtn,
      devices,
      setValue,
      required,
      onSubmit,
      loading,
      dialogVisible,
      allDevice,
      changeStead,
      showDialog
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
