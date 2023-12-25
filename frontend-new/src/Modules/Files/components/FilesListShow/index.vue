<template>
  <div>
    <div class="row q-col-gutter-md">
      <div v-for="(file, index) in filtersList" :key="file.uid">
        <q-card>
          <q-card-section class="row q-pa-xs" :class="{ 'items-center': !showPreview }">
            <div v-if="!showPreview" class="vertical-bottom">
              {{ ++index }}.
            </div>
            <FileItem :file="file" :get-url="getUrl" :show-preview="showPreview" />
            <div v-if="edit">
              <div class="text-secondary q-px-md row items-center">
                <div
                  class="text-red q-px-md"
                  @click="deleteFile(file)">
                  <DeleteIcon />
                </div>
              </div>
              <div v-if="file.upload.error" class="text-red">
                Ошибка {{ file.upload.errorsMessage }}
              </div>
            </div>
          </q-card-section>
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

export default {
  components: {
    FileItem,
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
