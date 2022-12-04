<template>
  <div class="pa3">
    {{ value }}
    <table>
      <tr v-for="(file, index) in value" :key="file.uid" class="pt4">
        <td>{{ ++index }}.</td>
        <td>
          <div>
            <i class="el-icon-document" />
            {{ file.name }}
          </div>
        </td>
        <td>
          <FileSize :size="file.size" class="q-px-sm text-grey-7" />
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
            class="text-red pl4"
            @click="deleteFile(file)"
          >
            <DeleteIcon />
          </div>
        </td>
        <td v-if="file.upload">
          <div
            class="text-red pl4"
          >
            ok
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import FileSize from '@/Modules/Files/components/FileSize'
import DeleteIcon from '@/Modules/Files/components/FilesListShow/DeleteIcon'

export default {
  components: {
    FileSize,
    DeleteIcon
  },
  props: {
    value: {
      type: Array,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
    }
  },
  methods: {
    deleteFile(file) {
      if (this.edit) {
        const data = this.value.filter(item => {
          return item !== file
        })
        this.$emit('input', data)
      }
    }
  }
}
</script>

<style scoped>

</style>
