<template>
  <div @click="downloadPayment">
    <slot name="default" v-bind:loading="fileDownload">
      <q-btn label="Скачать" color="primary" :loading="fileDownload" />
    </slot>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { exportFile } from 'quasar'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {},
  props: {
    func: { type: Function }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const fileDownload = ref(false)
    const downloadPayment = () => {
      fileDownload.value = true
      props.func()
        .then(response => {
          let fileName = response.headers['content-disposition'].split('filename=')[1].split(';')[0]
          try {
            fileName = decodeURIComponent(response.headers['content-disposition'].split("filename*=utf-8''")[1].split(';')[0])
          } catch (e) {
          }
          const status = exportFile(fileName, response.data)
          if (status !== true) {
            errorMessage('Ошибка получения файла')
          }
        })
        .catch(() => {
          errorMessage('Файл не найден')
        })
        .finally(() => {
          fileDownload.value = false
        })
    }
    return {
      data,
      fileDownload,
      downloadPayment
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
