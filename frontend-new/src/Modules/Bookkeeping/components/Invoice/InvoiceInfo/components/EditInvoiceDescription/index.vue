<template>
  <div>
    <InputAndSaveProxy
      v-model="data"
      :func="func"
      autogrow
      outlined
      :name="fieldName"
      @update:model-value="setValue"
      @success="setValue"
      @resset-data="setValue"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import InvoiceStatusSelect from 'src/Modules/Bookkeeping/components/Invoice/InvoiceStatusSelect/index.vue'
import InputAndSaveProxy from 'components/Input/InputAndSaveProxy/index.vue'
import { updateInvoiceField } from 'src/Modules/Bookkeeping/api/invoiceAdminApi'

export default defineComponent({
  components: {
    InvoiceStatusSelect,
    InputAndSaveProxy
  },
  props: {
    invoice: {
      type: Object,
      required: true
    },
    fieldName: {
      type: String,
      default: 'description'
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const func = (data) => {
      data.value = data.value.replaceAll("\n", '@')
      props.invoice.description[props.fieldName] = data.value
      return updateInvoiceField(props.invoice.id, data)
    }
    onMounted(() => {
      data.value = props.invoice.description[props.fieldName]?.replaceAll('@', "\n") || ''
    })
    const setValue = (data) => {
      props.invoice.description[props.fieldName] = data.replaceAll("\n", '@')
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

