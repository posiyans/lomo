<template>
  <div>
    <InputAndSaveProxy
      v-model="data"
      :func="func"
      autogrow
      outlined
      name="comment"
      @update:model-value="setValue"
      @success="setValue"
      @resset-data="setValue"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import InputAndSaveProxy from 'components/Input/InputAndSaveProxy/index.vue'
import { updateInvoiceField } from 'src/Modules/Bookkeeping/api/invoiceAdminApi'

export default defineComponent({
  components: {
    InputAndSaveProxy
  },
  props: {
    invoice: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref('')
    const func = (data) => {
      data.value = data.value.replaceAll("\n", '@')
      return updateInvoiceField(props.invoice.id, data)
    }
    onMounted(() => {
      data.value = props.invoice.options?.comment?.replaceAll('@', "\n") || ''
    })
    const setValue = (data) => {
      props.invoice.description.comment = data.replaceAll("\n", '@')
      emit('reload')
    }
    return {
      data,
      setValue,
      func
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

