<template>
  <div>
    <q-img :src="filePreviewUrl" :width="width">
      <template v-slot:error>
        <div class="absolute-full flex flex-center bg-negative text-white ellipsis">
          {{ name }}
        </div>
      </template>
    </q-img>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    file: {
      type: Object,
      required: true
    },
    name: {
      type: String,
      default: ''
    },
    width: {
      type: String,
      default: '150px'
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const filePreviewUrl = computed(() => {
      if (fileDownloadUrl.value) {
        return process.env.BASE_API + fileDownloadUrl.value + '&preview=1&width=300'
      }
    })
    const fileDownloadUrl = computed(() => {
      return props.file.url || props.file?.model?.url
    })
    return {
      data,
      fileDownloadUrl,
      filePreviewUrl
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
