<template>
  <div>
    <table>
      <tr v-for="(file, index) in filtersList" :key="file.uid" class="pt4">
        <td>{{ ++index }}.</td>
        <td>
          <div class="relative-position">
            <div class="row items-center q-col-gutter-sm">
              <div class="text-grey">
                <q-icon name="text_snippet" />
              </div>
              <div>
                {{ file.model.name }}
              </div>
            </div>
            <div v-if="file.upload && !file.upload.success && file.upload.process > 0 && file.upload.process < 1" class="absolute-bottom full-width">
              <q-linear-progress :value="file.upload.process" class="" track-color="grey" size="2px" />
            </div>
          </div>
        </td>
        <td>
          <FileSize :size="file.model.size" class="q-px-sm text-grey-7" />
        </td>
        <td>
          <div v-if="file.model.uid">
            <DownloadFileBtn :url-file="file.model.url" />
          </div>
        </td>
        <td v-if="edit">
          <div v-if="file.upload?.success" class="text-secondary q-px-md">
            <div
              class="text-red q-px-md"
              @click="deleteFile(file)">
              <DeleteIcon />
            </div>
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import FileSize from 'src/Modules/Files/components/FileSize/index.vue'
import DeleteIcon from 'src/Modules/Files/components/FilesListShow/DeleteIcon.vue'
import DownloadFileBtn from 'src/Modules/Files/components/DownloadFileBtn/index.vue'

export default {
  components: {
    FileSize,
    DeleteIcon,
    DownloadFileBtn
  },
  props: {
    modelValue: {
      type: Array,
      required: true
    },
    edit: {
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
