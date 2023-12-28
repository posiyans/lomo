<template>
  <div v-if="newRate" class="q-gutter-sm">
    <div>
      <q-input v-model="newRate.name" label="Название группы" outlined />
    </div>
    <div>
      <RateDependsSelect v-model="newRate.depends" outlined label="Расчитывается по" />
    </div>
    <div v-if="newRate.depends === 2">
      <q-input v-model="newRate.options.unit_name" label="Единица измерения" outlined />
    </div>
    <div>
      <q-select
        v-model="newRate.auto_invoice"
        label="Выставлять счета автоматически"
        map-options
        emit-value
        out
        :options="[
          {
            value: true,
            label: 'Да'
          },
          {
            value: false,
            label: 'Нет'
          },
        ]"
        outlined />
    </div>
    <div>
      <PaymentPeriodSelect v-model="newRate.payment_period" outlined label="Период оплаты" />
    </div>
    <div>
      Фразы для поиска в назначениях платежа
      <q-chip
        v-for="tag in newRate?.options?.tag"
        :key="tag"
        removable
        outline
        color="primary"
        text-color="white"
        :label="tag"
        :title="tag"
        @remove="removeTag(tag)"
      />
      <q-btn icon="add" rounded color="secondary" dense @click="addTag" />
    </div>
    <div class="text-right q-pt-md">
      <q-btn color="negative" flat label="Отмена" @click="cancel" />
      <q-btn color="primary" label="Сохранить" @click="saveData" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import InputDate from 'components/Input/InputDate/index.vue'
import { errorMessage } from 'src/utils/message'
import RateDependsSelect from 'src/Modules/Rate/components/RateDependsSelect/index.vue'
import PaymentPeriodSelect from 'src/Modules/Rate/components/PaymentPeriodSelect/index.vue'
import { useQuasar } from 'quasar'
import { updateRateGroup } from 'src/Modules/Rate/api/rateAdminApi'

export default defineComponent({
  components: {
    InputDate,
    RateDependsSelect,
    PaymentPeriodSelect
  },
  props: {
    rateGroup: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const readonly = ref(true)
    const data = ref(false)
    const newRate = ref(null)
    const $q = useQuasar()
    const cancel = () => {
      emit('cancel')
    }
    const saveData = () => {
      const data = {
        name: newRate.value.name,
        depends: newRate.value.depends,
        unit_name: newRate.value.options.unit_name,
        auto_invoice: newRate.value.auto_invoice,
        payment_period: newRate.value.payment_period,
        tag: newRate.value.options.tag || []
      }
      updateRateGroup(props.rateGroup.id, data)
        .then(res => {
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
    }
    const removeTag = (tag) => {
      newRate.value.options.tag.splice(newRate.value.options.tag.indexOf(tag), 1)
    }
    const addTag = () => {
      $q.dialog({
        title: 'Добавить фразу',
        message: 'Введите фразу которую необходимо добавить?',
        prompt: {
          model: '',
          type: 'text' // optional
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        newRate.value.options.tag.push(data)
        // console.log('>>>> OK, received', data)
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    }
    onMounted(() => {
      newRate.value = Object.assign({}, props.rateGroup)
    })
    return {
      data,
      readonly,
      addTag,
      cancel,
      removeTag,
      saveData,
      newRate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
