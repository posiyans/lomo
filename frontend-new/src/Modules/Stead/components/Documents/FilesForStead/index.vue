<template>
  <div>
    <FilesListShow v-model="files" :edit="edit" :key="key">
      <template v-slot:before>
        <AddDocumentToStead v-if="editAccess" :stead-id="steadId" @success="reload" />
      </template>
    </FilesListShow>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { geFilesForStead } from 'src/Modules/Stead/api/stead'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import { useFile } from 'src/Modules/Files/hooks/useFile'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import AddDocumentToStead from 'src/Modules/Stead/components/Documents/AddDocumentToStead/index.vue'

export default defineComponent({
  components: {
    FilesListShow,
    AddDocumentToStead
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const key = ref(1)
    const files = ref([])
    const listQuery = ref({})
    const authStore = useAuthStore()
    const edit = computed(() => {
      return authStore.roles.includes('superAdmin')
    })
    const editAccess = computed(() => {
      return authStore.checkPermission(['stead-edit', 'stead-show'])
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
    const reload = () => {
      key.value++
    }
    onMounted(() => {
      getData()
    })
    return {
      files,
      reload,
      editAccess,
      edit,
      key
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
