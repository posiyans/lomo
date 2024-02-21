<template>
  <div>
    <q-btn icon="delete" flat color="red" @click="deleteType" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { deleteAppealType } from 'src/Modules/Appeal/api/appealTypeApi'
import { useQuasar } from 'quasar'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {},
  props: {
    appealType: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const $q = useQuasar()
    const deleteType = () => {
      $q.dialog({
        title: 'Удалить?',
        message: 'Подтвердите удаление типа обращения (' + props.appealType.label + ')! ',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Удалить',
          color: 'primary'
        },
        persistent: true
      }).onOk(() => {
        deleteAppealType(props.appealType.id)
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
      deleteType
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
