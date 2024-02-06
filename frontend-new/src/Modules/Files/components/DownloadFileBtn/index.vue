<template>
  <div
    @click.stop="downloadFile"
  >
    <div v-if="loading" class="text-center">
      <q-spinner-facebook
        color="primary"
        size="1em"
      />
    </div>
    <div v-else>
      <slot>
        <div
          class="text-blue cursor-pointer text-weight-bold"
        >
          <q-icon name="download" />
          Скачать
        </div>
      </slot>
    </div>

  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import request from 'src/utils/request'
import { exportFile } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    urlFile: {
      type: String,
      required: true
    },
    fileName: {
      type: [Boolean, String],
      default: false
    }
  },
  setup(props, { emit }) {
    const loading = ref(false)
    const downloadFile = () => {
      loading.value = true
      request.defaults.timeout = ''
      request({
        url: props.urlFile,
        method: 'get',
        responseType: 'blob'
      })
        .then(response => {
          let fileName = response.headers['content-disposition'].split('filename=')[1].split(';')[0]
          try {
            fileName = decodeURIComponent(response.headers['content-disposition'].split("filename*=utf-8''")[1].split(';')[0])
          } catch (e) {
          }
          if (props.fileName) {
            fileName = props.fileName
          }
          const status = exportFile(fileName, response.data)
          if (status !== true) {
            this.$q.notify('Ошибка получения файла')
            console.log('Error: ' + status)
          }
        })
        .finally(() => {
          loading.value = false
        })
    }
    return {
      downloadFile,
      loading
    }
  }
})
</script>

<style scoped>

</style>
