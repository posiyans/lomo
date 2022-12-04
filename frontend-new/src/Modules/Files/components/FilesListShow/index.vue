<template>
  <div>
    <table>
      <tr v-for="(file, index) in modelValue" :key="file.uid" class="pt4">
        <td>{{++index}}.</td>
        <td>
          <div>
            <i class="el-icon-document"></i>
            {{file.name}}
          </div>
        </td>
        <td>
          <FileSize :size="file.size" class="q-px-sm text-grey-7"/>
         </td>
        <td>
          <div v-if="file.id">
            <a
              :href="'/api/v2/file/get?uid=' + file.uid"
              class="text-blue"
            >
              Скачать
            </a>
          </div>
        </td>
        <td v-if="edit">
          <div
            class="text-red q-px-md"
            @click="deleteFile(file)">
            <DeleteIcon />
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import FileSize from 'src/Modules/Files/components/FileSize/index.vue'
import DeleteIcon from 'src/Modules/Files/components/FilesListShow/DeleteIcon.vue'

export default {
  components: {
    FileSize,
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
    }
  },
  data () {
    return {
    }
  },
  methods: {
    deleteFile (file) {
      if (this.edit) {
        const data = this.modelValue.filter(item => {
          return item !== file
        })
        this.$emit('update:modelValue', data)
      }
    }
  }
}
</script>

<style scoped>

</style>
