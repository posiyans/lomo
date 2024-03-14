<template>
  <div v-if="filtersList.length > 0">
    <div class="row items-center">
      <slot name="before"></slot>
      <q-space />
      <div>
        <q-btn icon="list" dense flat :color="viewFormat === 'list' ? 'primary' : 'grey'" @click="changeFarmat('list')" />
        <q-btn icon="apps" dense flat :color="viewFormat === 'small' ? 'primary' : 'grey'" @click="changeFarmat('small')" />
        <q-btn icon="image" dense flat :color="viewFormat === 'image' ? 'primary' : 'grey'" @click="changeFarmat('image')" />
      </div>
    </div>
    <q-separator />
    <div>
      <ListView v-if="viewFormat === 'list'" :files="filtersList" />
      <SmallView v-if="viewFormat === 'small'" :files="filtersList" />
      <ImageView v-if="viewFormat === 'image'" :files="filtersList" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import DeleteIcon from 'src/Modules/Files/components/FilesListShow/DeleteIcon.vue'
import { copyToClipboard, useQuasar } from 'quasar'
import { successMessage } from 'src/utils/message'
import FileItem from './FileItem.vue'
import FilePreview from 'src/Modules/Files/components/FilePreview/index.vue'
import SmallView from './components/SmallView/index.vue'
import ListView from './components/ListView/index.vue'
import ImageView from './components/ImageView/index.vue'

export default defineComponent({
  components: {
    SmallView,
    ListView,
    ImageView,
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
    defaultView: {
      type: String,
      default: 'list'
    }
  },
  setup(props, { emit }) {
    const viewFormat = ref(props.defaultView || 'list')
    const filtersList = computed(() => {
      return props.modelValue.filter(item => {
        return !item.delete
      })
    })
    const emitUrl = (url) => {
      copyToClipboard(url)
        .then(() => {
          successMessage('Ссылка скопирована')
        })
    }
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
              const data = props.modelValue.map(item => {
                if (item === file) {
                  item.delete = true
                }
                return item
              })
              emit('update:modelValue', data)
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
    const changeFarmat = (val) => {
      viewFormat.value = val
    }
    return {
      changeFarmat,
      viewFormat,
      filtersList,
      deleteFile
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
