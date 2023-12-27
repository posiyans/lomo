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
            <div class="text-h6">Редактировать {{ rateGroup.name }}</div>
            <q-space />
            <q-btn icon="close" flat round dense v-close-popup />
          </div>
        </q-card-section>
        <q-card-section>
          <EditRateForm v-if="dialogVisible" :rate-group="rateGroup" @cancel="cancel" @success="success" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import EditRateForm from './EditForm.vue'

export default defineComponent({
  components: {
    EditRateForm
  },
  props: {
    rateGroup: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const dialogVisible = ref(false)
    const showDialog = () => {
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
      data,
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
