<template>
  <div>
    <AddFileBtn @add-file="addFile" />
    <FilesListShow v-model="localFile" edit />
  </div>
</template>

<script>
// пока не получилось

import AddFileBtn from '@/Modules/Files/components/AddFileBtn'
import FilesListShow from '@/Modules/Files/components/FilesListShow'
export default {
  components: {
    AddFileBtn,
    FilesListShow
  },
  props: {
    value: {
      type: Array,
      required: true
    },
    files: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      localFile: []
    }
  },
  mounted() {
    this.localFile = [...this.files]
  },
  methods: {
    addFile(val) {
      this.localFile.push(val)
    }
  }
}
</script>

<style scoped>
</style>
