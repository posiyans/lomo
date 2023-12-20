<template>
  <div>
    <input
      ref="btnRefd"
      type="file"
      class="hidden"
      :multiple="multiple"
      @change="change"
    />
    <div @click.stop="showDialog">
      <slot>
        <q-btn color="primary" :disabled="disabled" no-caps :label="label" />
      </slot>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useFile } from 'src/Modules/Files/hooks/useFile.js'
import { useQuasar } from 'quasar'
import { fileSize } from 'src/utils/filters.js'

export default defineComponent({
  components: {},
  props: {
    label: {
      type: String,
      default: 'Прикрепить файлы'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    parentType: {
      type: String,
      default: 'article'
    },
    maxSize: {
      type: Number,
      default: 5 * 1024 * 1024
    },
    parentUid: {
      type: String,
      required: true
    },
    multiple: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const btnRefd = ref(null)
    const $q = useQuasar()
    const showDialog = () => {
      console.log('klick')
      console.log(btnRefd.value)
      btnRefd.value.click()
    }
    const change = () => {
      console.log(btnRefd.value.files)
      const tmp = []
      if (btnRefd.value.files) {
        [...btnRefd.value.files].forEach(item => {
          if (item.size > props.maxSize) {
            $q.dialog({
              title: 'Ошибка',
              message: 'Файл ' + item.name + ' превышает максимальный размер в ' + fileSize(props.maxSize)
            })
          } else {
            const data = useFile()
            data.addFile(item, props.parentType, props.parentUid)
            // const data = useFile(item, props.parentType, props.parentUid)
            tmp.push(data)
          }
        })
      }
      emit('add-files', tmp)
    }
    return {
      showDialog,
      change,
      btnRefd
    }
  }
})
</script>

<style scoped>

</style>
