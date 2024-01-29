<template>
  <div>
    <div @click="onSubmit">
      <slot>
        <q-btn flat label="Удалить" color="negative" />
      </slot>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import { deleteInstrumentReading } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'
import { errorMessage } from 'src/utils/message'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    readingId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const $q = useQuasar()
    const onSubmit = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Удалить показания?',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Удалить',
          color: 'negative'
        },
        persistent: true
      }).onOk(() => {
        deleteInstrumentReading(props.readingId)
          .then(res => {
            emit('success')
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }
    return {
      onSubmit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
