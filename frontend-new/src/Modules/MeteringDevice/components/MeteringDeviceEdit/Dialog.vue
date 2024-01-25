<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn label="Редактировать" color="primary" />
      </slot>
    </div>
    <q-dialog
      v-model="dialogVisible"
      full-width
      @close="close"
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Редактировать</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <MeteringDeviceEdit :device="device" @delete="close" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import MeteringDeviceEdit from './index.vue'

export default defineComponent({
  components: {
    MeteringDeviceEdit
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
