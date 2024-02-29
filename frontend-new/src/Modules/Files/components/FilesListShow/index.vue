<template>
  <div>
    <div class="row q-col-gutter-md">
      <div v-for="(file) in filtersList" :key="file.uid">
        <q-card>
          <q-card-section class="row q-pa-xs" :class="{ 'items-center': !showPreview }">
            <FilePreview v-if="!showPreview" :file="file" width="36px" class="overflow-hidden br-05" style="width: 36px; height: 36px;" />
            <FileItem :file="file" :get-url="getUrl" :show-preview="showPreview" class="q-ml-xs" />
            <div v-if="edit">
              <div class="text-secondary row items-center">
                <div
                  class="text-red q-px-sm text-big-100"
                  @click="deleteFile(file)">
                  <DeleteIcon />
                </div>
              </div>
              <div v-if="file.upload.error" class="text-red">
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
  </div>
</template>

<script>
import DeleteIcon from 'src/Modules/Files/components/FilesListShow/DeleteIcon.vue'
import { copyToClipboard } from 'quasar'
import { successMessage } from 'src/utils/message'
import FileItem from './FileItem.vue'
import FilePreview from 'src/Modules/Files/components/FilePreview/index.vue'

export default {
  components: {
    FileItem,
    FilePreview,
    DeleteIcon
  },
  props: {
    modelValue: {
      type: Array,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
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
  data() {
    return {}
  },
  computed: {
    filtersList() {
      return this.modelValue.filter(item => {
        return !item.delete
      })
    }
  },
  methods: {
    emitUrl(url) {
      copyToClipboard(url)
        .then(() => {
          successMessage('Ссылка скопирована')
        })
    },
    deleteFile(file) {
      if (this.edit) {
        this.$q.dialog({
          title: 'Внимание?',
          message: 'Удалить файл?',
          cancel: true,
          persistent: true
        }).onOk(() => {
          file.deleteFile()
            .then(() => {
              const data = this.modelValue.map(item => {
                if (item === file) {
                  item.delete = true
                }
                return item
              })
              this.$emit('update:modelValue', data)
            })
            .catch(() => {
              this.$q.notify(
                {
                  message: 'Ошибка удаления файла',
                  type: 'negative'
                }
              )
            })
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
