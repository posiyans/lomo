<template>
  <div>
    <FilesListShow v-model="files" :edit="edit" class="q-gutter-sm" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { geFilesForStead } from 'src/Modules/Stead/api/stead'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import { useFile } from 'src/Modules/Files/hooks/useFile'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    FilesListShow
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const files = ref([])
    const listQuery = ref({})
    const autnStore = useAuthStore()
    const edit = computed(() => {
      return autnStore.roles.includes('superAdmin')
    })
    const getData = () => {
      geFilesForStead(props.steadId, listQuery.value)
        .then(res => {
          files.value = []
          res.data.data.forEach(item => {
            const file = useFile()
            file.init(item)
            files.value.push(file)
          })
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      files,
      edit
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
