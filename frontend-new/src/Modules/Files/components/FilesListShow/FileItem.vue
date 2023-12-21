<template>
  <td class="vertical-bottom">
    <FilePreview v-if="showPreview" :file="file" :name="fileName" />
    <div class="relative-position">
      <div class="row items-center q-col-gutter-sm">
        <div class="text-grey">
          <q-icon name="text_snippet" size="sm" />
        </div>
        <div>
          {{ fileName }}
        </div>
      </div>
      <div v-if="file.upload && !file.upload.success && file.upload.process > 0 && file.upload.process < 1" class="absolute-bottom full-width">
        <q-linear-progress :value="file.upload.process" class="" track-color="grey" size="2px" />
      </div>
    </div>
  </td>
  <td class="vertical-bottom">
    <FileSize :size="fileSize" class="q-px-sm text-grey-7" />
  </td>
  <td class="vertical-bottom">
    <div class="row items-center q-col-gutter-sm">
      <div v-if="fileDownloadUrl">
        <DownloadFileBtn :url-file="fileDownloadUrl" />
      </div>
      <div v-if="getUrl" class="cursor-pointer text-secondary" @click="emitUrl(fileDownloadUrl)">
        Скопировать ссылку
      </div>
    </div>
  </td>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import FileSize from 'src/Modules/Files/components/FileSize/index.vue'
import DownloadFileBtn from 'src/Modules/Files/components/DownloadFileBtn/index.vue'
import { copyToClipboard } from 'quasar'
import { successMessage } from 'src/utils/message'
import FilePreview from 'src/Modules/Files/components/FilePreview/index.vue'


export default defineComponent({
  components: {
    FileSize,
    FilePreview,
    DownloadFileBtn
  },
  props: {
    file: {
      type: Object,
      required: true
    },
    getUrl: {
      type: Boolean,
      default: false
    },
    showPreview: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const fileSize = computed(() => {
      return props.file.size || props.file.model?.size
    })
    const fileName = computed(() => {
      return props.file.name || props.file.model?.name
    })
    const fileDownloadUrl = computed(() => {
      return props.file.url || props.file?.model?.url
    })
    const emitUrl = (url) => {
      copyToClipboard(url)
        .then(() => {
          successMessage('Ссылка скопирована')
        })
    }
    return {
      data,
      emitUrl,
      fileDownloadUrl,
      fileSize,
      fileName
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

