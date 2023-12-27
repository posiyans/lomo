<template>
  <div class="q-gutter-sm">

    <div>
      <q-input v-model="newRate.name" label="Название" outlined />
    </div>
    <div>
      <q-input v-model="newRate.description" label="Назначение платежа" outlined />
    </div>
    <div class="text-right">
      <q-btn color="negative" flat label="Отмена" @click="cancel" />
      <q-btn color="primary" label="Сохранить" @click="saveData" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import InputDate from 'components/Input/InputDate/index.vue'
import { updateRateType } from 'src/Modules/Rate/api/rateAdminApi'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {
    InputDate
  },
  props: {
    rate: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const readonly = ref(true)
    const data = ref(false)
    const newRate = ref({})

    const cancel = () => {
      emit('cancel')
    }
    const saveData = () => {
      const data = {
        description: newRate.value.description,
        name: newRate.value.name
      }
      updateRateType(props.rate.id, data)
        .then(res => {
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
    }

    onMounted(() => {
      newRate.value = Object.assign({}, props.rate)
    })
    return {
      data,
      readonly,
      cancel,
      saveData,
      newRate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
