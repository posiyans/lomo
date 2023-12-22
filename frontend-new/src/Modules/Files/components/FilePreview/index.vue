<template>
  <div>
    <div class="cursor-pointer" @click="showImgAction">
      <q-img :src="filePreviewUrl" :width="width" :style="{'min-height': width}" class="br-05">
        <template v-slot:error>
          <div class="absolute-full flex flex-center bg-negative text-white ellipsis">
            {{ name }}
          </div>
        </template>
        <div class="absolute-bottom ellipsis q-pa-none" style="padding: 0 5px;">
          {{ name }}
        </div>
      </q-img>
    </div>
    <ShowMediaBlock
      v-if="showImg"
      :src-img="filePreviewUrl"
      :file-name="name"
      @close="showImg = false"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowMediaBlock from 'src/Modules/Files/components/ShowMediaBlock/index.vue'

export default defineComponent({
  components: {
    ShowMediaBlock
  },
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
      default: '120px'
    }
  },
  setup(props, { emit }) {
    const showImg = ref(false)
    const showImgAction = () => {
      showImg.value = true
    }
    const filePreviewUrl = computed(() => {
      if (fileDownloadUrl.value) {
        return process.env.BASE_API + fileDownloadUrl.value + '&preview=1&width=300'
      }
    })
    const fileDownloadUrl = computed(() => {
      return props.file.url || props.file?.model?.url
    })
    return {
      showImg,
      showImgAction,
      fileDownloadUrl,
      filePreviewUrl
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
