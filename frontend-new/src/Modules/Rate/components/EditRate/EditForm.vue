<template>
  <div class="q-gutter-sm">
    <div>
      <InputNumber v-model="newRate.rate.ratio_a" :label="rate.depends === 1 ? 'Тариф на 1 сотку' : rateLabel" outlined @update:model-value="chandeRate" />
    </div>
    <div v-if="rate.depends === 1">
      <InputNumber v-model="newRate.rate.ratio_b" label="Тариф на 1 участок" outlined @update:model-value="chandeRate" />
    </div>
    <div>
      <q-input v-model="newRate.rate.description" :readonly="readonly" label="Описание оплаты" outlined>
        <template v-slot:append>
          <q-btn round dense flat icon="edit" color="grey-5" @click="readonly = !readonly" />
        </template>
      </q-input>
    </div>
    <div>
      <InputDate v-model="newRate.rate.date_start" label="Дата начала действия тарифа" outlined />
    </div>
    <div class="text-right">
      <q-btn color="negative" flat label="Отмена" @click="cancel" />
      <q-btn color="primary" label="Сохранить" @click="saveData" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import InputDate from 'components/Input/InputDate/index.vue'
import { updateRate } from 'src/Modules/Rate/api/rateAdminApi'
import { errorMessage } from 'src/utils/message'
import InputNumber from './InputNumberFloat.vue'

export default defineComponent({
  components: {
    InputDate,
    InputNumber
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
    const newRate = ref({
      rate: {}
    })
    const rateLabel = computed(() => {
      return 'Тариф на 1 ' + props.rate.unit_name
    })
    const chandeRate = () => {
      if (props.rate.depends === 2) {
        newRate.value.rate.description = props.rate.rate.ratio_a + ' руб*' + props.rate.unit_name
      } else if (props.rate.depends === 1) {
        let text = ''
        if (props.rate.rate.ratio_a > 0) {
          text += props.rate.rate.ratio_a + ' руб с сотки'
        }
        if (props.rate.rate.ratio_b > 0) {
          if (props.rate.rate.ratio_a > 0) {
            text += ' и '
          }
          text += props.rate.rate.ratio_b + ' руб с участка'
        }
        newRate.value.rate.description = text
      } else {
        newRate.value.rate.description = ''
      }

    }
    const cancel = () => {
      emit('cancel')
    }
    const saveData = () => {
      const data = {
        description: newRate.value.rate.description,
        ratio_a: newRate.value.rate.ratio_a || 0,
        ratio_b: newRate.value.rate.ratio_b || 0,
        date_start: newRate.value.rate.date_start
      }
      console.log(data)
      updateRate(props.rate.id, data)
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
      rateLabel,
      saveData,
      chandeRate,
      newRate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
