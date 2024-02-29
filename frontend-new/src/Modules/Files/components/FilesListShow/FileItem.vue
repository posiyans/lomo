<template>
  <div>
    <FilePreview v-if="showPreview" :file="file" :name="fileName" :width="imgWidth" />
    <div v-if="!showPreview">
      <div class="relative-position">
        <div class="row items-center q-col-gutter-sm">
          <div class="ellipsis" style="max-width: 200px;">
            <q-tooltip>
              {{ fileName }}
            </q-tooltip>
            {{ fileName }}
          </div>
        </div>
      </div>
      <div class="row items-center">
        <FileSize :size="fileSize" class="text-grey-7 text-small-85" />
        <q-space />

        <div class="row items-center q-col-gutter-sm q-px-sm">
          <div v-if="fileDownloadUrl">
            <DownloadFileBtn :url-file="fileDownloadUrl" />
          </div>
          <div v-if="getUrl" class="cursor-pointer text-secondary" @click="emitUrl(fileDownloadUrl)">
            Скопировать ссылку
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import FileSize from 'src/Modules/Files/components/FileSize/index.vue'
import DownloadFileBtn from 'src/Modules/Files/components/DownloadFileBtn/index.vue'
import { copyToClipboard, useQuasar } from 'quasar'
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
    const $q = useQuasar()
    const fileSize = computed(() => {
      return props.file.size || props.file.model?.size
    })
    const fileName = computed(() => {
      return props.file.name || props.file.model?.name
    })
    const fileDownloadUrl = computed(() => {
      return props.file.url || props.file?.model?.url
    })
    const imgWidth = computed(() => {
      if ($q.screen.width < 450) {
        return ($q.screen.width - 150) / 2 + 'px'
      }
      return '150px'
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
      imgWidth,
      fileDownloadUrl,
      fileSize,
      fileName
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

