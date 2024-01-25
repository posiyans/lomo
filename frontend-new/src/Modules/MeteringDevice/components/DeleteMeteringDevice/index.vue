<template>
  <div>
    <div @click="deleteDevice">
      <slot>
        <q-btn color="negative" label="Удалить" />
      </slot>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { deleteMeteringDevice } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'
import { errorMessage } from 'src/utils/message'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    deviceId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const $q = useQuasar()
    const deleteDevice = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Подтвердите удаление прибора учета!',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'primary'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Удалить',
          color: 'negative'
        },
        persistent: true
      }).onOk(() => {
        deleteMeteringDevice(props.deviceId)
          .then(() => {
            emit('success')
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }
    return {
      data,
      deleteDevice
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
