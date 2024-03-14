<template>
  <div class="q-gutter-sm">
    <div v-for="(file, index) in files" :key="file.uid">
      <div class="row items-center q-col-gutter-sm">
        <div>
          {{ ++index }}.
        </div>
        <div>
          <div v-if="file.model?.description">
            <div>
              {{ file.model?.description }}
            </div>
            <div class="text-small-65 o-60">
              {{ file.model?.name || file.name }}
            </div>
          </div>
          <div v-else>{{ file.model?.name || file.name }}</div>
        </div>
        <FileSize :size=" file.model?.size || file.size" class="text-grey-7 text-small-85" />
        <div v-if="file?.model?.url || file.url">
          <DownloadFileBtn :url-file="file?.model?.url || file.url" />
        </div>
        <div v-if="edit">
          <div class="text-secondary row items-center">
            <div
              class="text-red q-px-sm text-big-100"
              @click="deleteFile(file)">
              <DeleteIcon />
            </div>
          </div>
          <div v-if="file.upload?.error" class="text-red">
            Ошибка {{ file.upload.errorsMessage }}
          </div>
        </div>
      </div>
      <div v-if="file.upload && !file.upload.success && file.upload.process > 0 && file.upload.process < 1" class="absolute-bottom full-width">
        <q-linear-progress :value="file.upload.process" class="" track-color="grey" size="3px" />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import FileItem from './../../FileItem.vue'
import FilePreview from 'src/Modules/Files/components/FilePreview/index.vue'
import { useQuasar } from 'quasar'
import FileSize from 'src/Modules/Files/components/FileSize/index.vue'
import DownloadFileBtn from 'src/Modules/Files/components/DownloadFileBtn/index.vue'
import DeleteIcon from 'src/Modules/Files/components/FilesListShow/DeleteIcon.vue'

export default defineComponent({
  components: {
    FileItem,
    FilePreview,
    FileSize,
    DownloadFileBtn,
    DeleteIcon
  },
  props: {
    files: {
      type: Array,
      required: true
    },
    getUrl: {
      type: Boolean,
      default: false
    },
    edit: {
      type: Boolean,
      default: false
    },
  },
  setup(props, { emit }) {
    const data = ref(false)
    const $q = useQuasar()
    const deleteFile = (file) => {
      if (props.edit) {
        $q.dialog({
          title: 'Внимание?',
          message: 'Удалить файл?',
          cancel: true,
          persistent: true
        }).onOk(() => {
          file.deleteFile()
            .then(() => {
              const data = props.files.map(item => {
                if (item === file) {
                  item.delete = true
                }
                return item
              })
            })
            .catch(() => {
              $q.notify(
                {
                  message: 'Ошибка удаления файла',
                  type: 'negative'
                }
              )
            })
        })
      }
    }
    return {
      data,
      deleteFile
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
