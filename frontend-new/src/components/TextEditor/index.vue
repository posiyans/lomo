<template>
  <div>
    <EditorImage
      v-if="addFileDialogVisible"
      @success="addFile"
      :parent-uid="parentUid"
      :parent-type="parentType"
      @hide="closeDialog" />
    <q-editor
      :model-value="modelValue"
      :dense="$q.screen.lt.md"
      :toolbar="toolBar"
      :definitions="{
        upload: {
          tip: 'Загрузить картинку',
          icon: 'image',
          label: 'Add',
          handler: uploadIt
        }
      }"
      @update:model-value="setValue"
    />
  </div>
</template>

<script>
import EditorImage from './components/EditorImage.vue'
import { computed, ref } from 'vue'

export default {
  components: {
    EditorImage
  },
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    addImage: {
      type: Boolean,
      default: false
    },
    parentType: {
      type: String,
      default: 'article'
    },
    parentUid: {
      type: String,
      required: true
    }
  },
  setup(props, { emit }) {
    const toolBar = computed(() => {
      if (props.addImage) {
        return [
          ['left', 'center', 'right', 'justify'],
          ['bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
          ['token', 'hr', 'link', 'custom_btn'],
          ['fullscreen', 'upload'],
          ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
          ['undo', 'redo'],
          ['viewsource']
        ]
      }
      return [
        ['left', 'center', 'right', 'justify'],
        ['bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
        ['token', 'hr', 'link', 'custom_btn'],
        ['fullscreen'],
        ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
        ['undo', 'redo'],
        ['viewsource']
      ]
    })
    const addFileDialogVisible = ref(false)
    const setValue = (val) => {
      emit('update:model-value', val)
    }
    const uploadIt = () => {
      addFileDialogVisible.value = true
    }
    const closeDialog = () => {
      addFileDialogVisible.value = false
    }
    const addFile = (files) => {
      let val = props.modelValue
      let path = ''
      if (process.env.DEV) {
        path = process.env.BASE_API
      }
      files.forEach(item => {
        const a = `<img src="${path}${item.model.url}" class="article-img" >`
        val = val + a
      })
      emit('update:model-value', val)
    }
    return {
      toolBar,
      addFileDialogVisible,
      closeDialog,
      setValue,
      addFile,
      uploadIt
    }
  }
}
</script>
