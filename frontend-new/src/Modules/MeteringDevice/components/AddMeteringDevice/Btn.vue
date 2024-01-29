<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn label="Добавить" icon="add" color="primary" />
      </slot>
    </div>
    <q-dialog
      v-model="dialogVisible"
      full-width
      @close="close"
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Добавить прибор учета</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <AddMeteringDeviceEdit
            :stead-id="steadId"
            @success="close" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import AddMeteringDeviceEdit from './index.vue'

export default defineComponent({
  components: {
    AddMeteringDeviceEdit
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const dialogVisible = ref(false)
    const showDialog = () => {
      dialogVisible.value = true
    }
    const close = () => {
      dialogVisible.value = false
      emit('close')
    }
    return {
      data,
      close,
      dialogVisible,
      showDialog
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
