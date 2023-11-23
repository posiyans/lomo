<template>
  <div>
    <AddFileBtn
      :multiple="multiple"
      :disabled="disabled"
      @add-file="addFile"
    />
    <FilesListShow :model-value="modelValue" edit @update:model-value="deleteFile" />
  </div>
</template>

<script>
// пока не получилось

import AddFileBtn from 'src/Modules/Files/components/AddFileBtn/index.vue'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'

export default {
  components: {
    AddFileBtn,
    FilesListShow
  },
  props: {
    modelValue: {
      type: Array,
      default: () => []
    },
    label: {
      type: String,
      default: 'Прикрепить файлы'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    multiple: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    deleteFile(val) {
      this.$emit('update:model-value', val)
    },
    addFile(val) {
      const tmp = [...this.modelValue]
      val.forEach(item => {
        tmp.push(item)
      })
      this.$emit('update:model-value', tmp)
    }
  }
}
</script>

<style scoped>
</style>
