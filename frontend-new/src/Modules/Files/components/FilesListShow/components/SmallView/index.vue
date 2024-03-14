<template>
  <div class="row items-center q-col-gutter-sm q-pt-sm">
    <div v-for="file in files" :key="file.uid">
      <q-card>
        <q-card-section v-if="file.model?.description" class="q-py-none">
          <div>{{ file.model?.description }}</div>
        </q-card-section>
        <q-card-section class="row q-pa-xs items-center">
          <FilePreview :file="file" width="36px" class="overflow-hidden br-05" style="width: 36px; height: 36px;" />
          <FileItem :file="file" :get-url="getUrl" :show-preview="false" class="q-ml-xs" />
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
        </q-card-section>
        <div v-if="file.upload && !file.upload.success && file.upload.process > 0 && file.upload.process < 1" class="absolute-bottom full-width">
          <q-linear-progress :value="file.upload.process" class="" track-color="grey" size="3px" />
        </div>
      </q-card>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import FileItem from './../../FileItem.vue'
import FilePreview from 'src/Modules/Files/components/FilePreview/index.vue'
import { useQuasar } from 'quasar'
import DeleteIcon from 'src/Modules/Files/components/FilesListShow/DeleteIcon.vue'

export default defineComponent({
  components: {
    FileItem,
    FilePreview,
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
