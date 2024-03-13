<template>
  <div>
    <AddFileBtn
      parent-type="stead"
      :parent-uid="steadId"
      acceptFileType="*"
      @add-files="addFile"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import AddFileBtn from 'src/Modules/Files/components/AddFileBtn/index.vue'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {
    AddFileBtn
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const q = useQuasar()
    const addFile = (files) => {
      console.log('file')
      console.log(files)
      q.dialog({
        title: 'Описание',
        message: 'Добавте описание к файлу',
        prompt: {
          model: '',
          type: 'text' // optional
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        const tmp = {
          description: data,
          file: files[0]
        }
        const file = files[0]
        file.model.description = data
        file.sendFileToServer()
          .then(() => {
            emit('success')
          })
      })

    }
    return {
      addFile
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
