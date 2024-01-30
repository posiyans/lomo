<template>
  <div>
    <InputAndSaveProxy
      v-model="status"
      :func="func"
      name="is_paid"
      @update:model-value="setValue"
    >
      <template #default="{ modelValue, setValue }">
        <InvoiceStatusSelect
          :model-value="modelValue"
          outlined
          dense
          @update:model-value="setValue"
        />
      </template>
    </InputAndSaveProxy>

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
    }
  },
  setup(props, { emit }) {
    const status = ref(0)
    const func = (data) => {
      return updateInvoiceField(props.invoice.id, data)
    }
    onMounted(() => {
      status.value = props.invoice.is_paid ? 1 : 0
    })
    const setValue = (val) => {
      if (val === 1) {
        props.invoice.is_paid = true
      } else {
        props.invoice.is_paid = false
      }
    }
    return {
      status,
      setValue,
      func
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

