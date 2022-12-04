<template>
  <div>
    <input
        ref="file"
        type="file"
        class="hidden"
        multiple
        @change="change"
    />
    <q-btn color="primary" :disabled="disabled" no-caps :label="label" @click="showDialog" />
  </div>
</template>

<script>
import { uid } from 'quasar'

export default {
  props: {
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
    showDialog() {
      this.$refs.file.click()
    },
    change() {
      this.$refs.file.files.forEach(item => {
        const data = {
          uid: uid(),
          upload: false,
          name: item.name,
          size: item.size,
          type: item.type,
          file: item
        }
        console.log(item)
        this.$emit('add-file', data)
      })
    }
  }
}
</script>

<style scoped>
</style>
