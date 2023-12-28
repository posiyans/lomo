<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn label="Изменить" color="primary" />
      </slot>
    </div>
    <q-dialog v-model="dialogVisible">
      <q-card style="min-width: 450px;">
        <q-card-section class="q-pb-none">
          <div class="row items-center">
            <div class="text-h6">{{ dialogLabel }}</div>
            <q-space />
            <q-btn icon="close" flat round dense v-close-popup />
          </div>
          <div class="text-grey text-small-80">
            {{ dataRateType.description }}
          </div>
        </q-card-section>
        <q-card-section>
          <EditRateForm v-if="dialogVisible" :rate="dataRateType" :add="add" @cancel="cancel" @success="success" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import EditRateForm from './EditForm.vue'

export default defineComponent({
  components: {
    EditRateForm
  },
  props: {
    rate: {
      type: Object,
      default: () => {
      }
    },
    add: {
      type: Boolean,
      default: false
    },
    rateGroupId: {
      type: [String, Number],
      default: null
    },
  },
  setup(props, { emit }) {
    const dataRateType = ref({})
    const dialogVisible = ref(false)
    const dialogLabel = computed(() => {
      if (props.add) {
        return 'Добавить тип тарифа'
      } else {
        return 'Редактировать тариф ' + props.rate.name
      }
    })
    const showDialog = () => {
      if (props.add) {
        dataRateType.value.rate_group_id = props.rateGroupId
        dataRateType.value.name = ''
        dataRateType.value.description = ''
      } else {
        dataRateType.value = props.rate
      }
      dialogVisible.value = true
    }
    const cancel = () => {
      dialogVisible.value = false
    }
    const success = () => {
      dialogVisible.value = false
      emit('success')
    }
    return {
      dataRateType,
      dialogLabel,
      success,
      cancel,
      showDialog,
      dialogVisible
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
