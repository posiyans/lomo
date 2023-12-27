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
            <div class="text-h6">Редактировать тариф {{ rate.name }}</div>
            <q-space />
            <q-btn icon="close" flat round dense v-close-popup />
          </div>
          <div class="text-grey text-small-80">
            {{ rate.description }}
          </div>
        </q-card-section>
        <q-card-section>
          <EditRateForm v-if="dialogVisible" :rate="rate" @cancel="cancel" @success="success" />
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
    rate: {
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
    const success = () => {
      dialogVisible.value = false
      emit('success')
    }
    const cancel = () => {
      dialogVisible.value = false
    }
    return {
      data,
      cancel,
      success,
      showDialog,
      dialogVisible
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
