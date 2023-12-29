<template>
  <div @click="download">
    <slot>
      <q-btn label="Квитанция" color="negative" flat :loading="loading" />
    </slot>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { exportFile, useQuasar } from 'quasar'
import { getReceiptForInvoice } from 'src/Modules/Receipt/api/receipt'

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
    const loading = ref(false)
    const $q = useQuasar()
    const download = () => {
      loading.value = true
      const data = {
        invoice_id: props.invoiceId
      }
      getReceiptForInvoice(data)
        .then(response => {
          let fileName = response.headers['content-disposition'].split('filename=')[1].split(';')[0]
          try {
            fileName = decodeURIComponent(response.headers['content-disposition'].split("filename*=utf-8''")[1].split(';')[0])
          } catch (e) {
          }
          const status = exportFile(fileName, response.data)
          if (status !== true) {
            $q.notify('Ошибка получения файла')
            console.log('Error: ' + status)
          }
        })
        .finally(() => {
          loading.value = false
        })
    }
    return {
      data,
      loading,
      download
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
