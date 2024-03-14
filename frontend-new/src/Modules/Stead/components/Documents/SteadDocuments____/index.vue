<template>
  <div>
    <AddDocumentToStead v-if="edit" :stead-id="steadId" class="q-pb-sm" @success="reload" />
    <FilesForStead :stead-id="steadId" :key="key">
      <template v-slot:#before>
        <div>

          xvxcvsdv
        </div>
      </template>
    </FilesForStead>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import AddDocumentToStead from 'src/Modules/Stead/components/Documents/AddDocumentToStead/index.vue'
import FilesForStead from 'src/Modules/Stead/components/Documents/FilesForStead/index.vue'

export default defineComponent({
  components: {
    AddDocumentToStead,
    FilesForStead
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const key = ref(1)
    const data = ref(false)
    const reload = () => {
      key.value++
    }
    return {
      data,
      key,
      reload
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
