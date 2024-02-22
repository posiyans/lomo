<template>
  <div>
    <q-btn icon="add" flat color="primary" @click="addType" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import { createAppealType } from 'src/Modules/Appeal/api/appealTypeApi'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const $q = useQuasar()
    const addType = () => {
      $q.dialog({
        title: 'Добавить?',
        message: 'Укажите имя нового типа обращения',
        prompt: {
          model: '',
          type: 'text' // optional
        },
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          label: 'Добавить',
          color: 'primary'
        },
        persistent: true
      }).onOk(label => {
        const tmp = {
          label
        }
        createAppealType(tmp)
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
      addType
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
