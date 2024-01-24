<template>
  <div class="q-gutter-sm">
    <q-form
      @submit="onSubmit"
      class="q-gutter-md"
    >
      <div>
        <RateGroupSelect
          v-model="rateGroup"
          :params="{depends: 2}"
          label="Тип прибора"
          outlined
          auto-select
          :rules="[required]"
        />
      </div>
      <div>
        <RateTypeForGroupSelect
          v-model="device.rate_type_id"
          :rate-group="rateGroup"
          label="Тариф прибора"
          outlined
          auto-select
          :rules="[required]"
        />
      </div>
      <div>
        <q-input
          v-model="device.device_brand"
          outlined
          label="Модель прибора"
        />
      </div>
      <div>
        <q-input
          v-model="device.serial_number"
          outlined
          label="Серийный номер"
        />
      </div>
      <div>
        <InputDate
          v-model="device.installation_date"
          outlined
          label="Дата установки"
        />
      </div>
      <div>
        <q-input
          v-model="device.initial_data"
          outlined
          label="Начальные показания"
          :rules="[isNumber]"
        />
      </div>
      <div>
        <InputDate
          v-model="device.verification_date"
          outlined
          label="Дата следующей поверки"
        />
      </div>
      <div>
        <q-input
          v-model="device.description"
          outlined
          autogrow
          label="Примечание"
        />
      </div>
      <div>
        <q-btn-toggle
          v-model="device.active"
          outlined
          label="Дата установки"
          toggle-color="teal"
          no-caps
          :options="[
              {label: 'Активный', value: 1},
              {label: 'Не рабочий', value: 0}
            ]"
        />
      </div>
      <div class="text-right">
        <q-btn color="primary" label="Добавить" type="submit" />
      </div>
    </q-form>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import InputDate from 'components/Input/InputDate/index.vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import RateTypeForGroupSelect from 'src/Modules/Rate/components/RateTypeForGroupSelect/index.vue'
import InputNumber from 'components/Input/InputNumber/index.vue'
import { isNumber, required } from 'src/utils/validators.js'

export default defineComponent({
  components: {
    RateGroupSelect,
    RateTypeForGroupSelect,
    InputNumber,
    InputDate
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const rateGroup = ref('')
    const device = ref({
      stead_id: props.steadId,
      rate_type_id: '',
      initial_data: 0,
      description: '',
      active: 1,
      serial_number: '',
      device_brand: '',
      installation_date: '',
      verification_date: ''
    })
    const onSubmit = () => {

    }
    return {
      data,
      device,
      onSubmit,
      isNumber,
      required,
      rateGroup
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
