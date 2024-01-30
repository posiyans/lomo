<template>
  <div class="inline-block">
    <div @click="clickAction">
      <slot>
        <q-btn label="Удалить счет" color="negative" />
      </slot>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import { deleteInvoice } from 'src/Modules/Bookkeeping/api/invoiceAdminApi'

export default defineComponent({
  components: {},
  props: {
    invoiceId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const $q = useQuasar()
    const clickAction = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Удалить счет № ' + props.invoiceId + ' ?',
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
        deleteInvoice(props.invoiceId)
          .then(() => {
            emit('success')
          })
      })
    }
    return {
      data,
      clickAction
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
