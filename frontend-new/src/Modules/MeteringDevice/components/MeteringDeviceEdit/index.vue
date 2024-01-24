<template>
  <div class="q-gutter-sm">
    <div>
      <InputAndSaveProxy
        v-model="editDevice.device_brand"
        outlined
        label="Модель прибора"
        name="device_brand"
        :func="func"
      />
    </div>
    <div>
      <InputAndSaveProxy
        v-model="editDevice.serial_number"
        outlined
        label="Серийный номер"
        name="serial_number"
        :func="func"
      />
    </div>
    <div>
      <InputAndSaveProxy
        v-model="editDevice.installation_date"
        outlined
        label="Дата установки"
        name="installation_date"
        :func="func"
      >
        <template #default="{ modelValue, setValue }">
          <InputDate outlined :model-value="modelValue" label="Дата установки" @update:model-value="setValue" />
        </template>
      </InputAndSaveProxy>
    </div>
    <div>
      <InputAndSaveProxy
        v-model="editDevice.initial_data"
        outlined
        label="Начальные показания"
        name="initial_data"
        :func="func"
      />
    </div>
    <div>
      <InputAndSaveProxy
        v-model="editDevice.verification_date"
        outlined
        label="Дата следующей поверки"
        name="verification_date"
        :func="func"
      >
        <template #default="{ modelValue, setValue }">
          <InputDate outlined label="Дата следующей поверки" :model-value="modelValue" @update:model-value="setValue" />
        </template>
      </InputAndSaveProxy>
    </div>
    <div>
      <InputAndSaveProxy
        v-model="editDevice.description"
        outlined
        autogrow
        label="Примечание"
        name="description"
        :func="func"
      />
    </div>
    <div>
      <InputAndSaveProxy
        v-model="editDevice.active"
        outlined
        label="Дата установки"
        name="active"
        :func="func"
      >
        <template #default="{ modelValue, setValue }">
          <div class="q-pt-xs">
            <q-btn-toggle
              :model-value="modelValue"
              toggle-color="teal"
              no-caps
              :options="[
              {label: 'Активный', value: 1},
              {label: 'Не рабочий', value: 0}
            ]"
              @update:model-value="setValue"
            />
          </div>
        </template>
      </InputAndSaveProxy>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import InputAndSaveProxy from 'components/Input/InputAndSaveProxy/index.vue'
import InputDate from 'components/Input/InputDate/index.vue'
import { updateFieldMeteringDevice } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'

export default defineComponent({
  components: {
    InputAndSaveProxy,
    InputDate
  },
  props: {
    device: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const dialogVisible = ref(false)
    const editDevice = ref({})
    const func = (data) => {
      return updateFieldMeteringDevice(props.device.id, data)
    }
    const showDialog = () => {
      dialogVisible.value = true
    }
    onMounted(() => {
      editDevice.value = props.device
    })
    return {
      data,
      func,
      editDevice,
      dialogVisible,
      showDialog
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
