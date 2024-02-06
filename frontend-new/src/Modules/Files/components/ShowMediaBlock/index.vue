<template>
  <div>
    <div class="image-viewer__wrapper" style="z-index: 2000;" @click="closeForm">
      <div
        class="image-viewer__mask"
      />
      <div class="image-viewer__canvas">
        <div class="absolute-top-right">
          <q-btn icon="highlight_off" color="white" flat @click="closeForm" />
        </div>
        <img :src="filePreviewUrl" alt="">
        <div class="absolute-bottom text-teal text-center row items-center justify-center bg-orange-2 q-py-xs">
          <div class="q-mr-md">
            {{ fileName }}
          </div>
          <DownloadFileBtn :url-file="fileUrl" class="text-big-50" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref } from 'vue'
import DownloadFileBtn from 'src/Modules/Files/components/DownloadFileBtn/index.vue'

export default {
  components: {
    DownloadFileBtn
  },
  props: {
    srcImg: {
      type: String,
      required: true
    },
    fileUrl: {
      type: String,
      required: true
    },
    fileName: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const filePreviewUrl = computed(() => {
      return process.env.BASE_API + props.fileUrl + '&preview=1&width=300'
    })
    const hideControl = ref(true)
    const closeForm = () => {
      emit('close')
    }
    return {
      hideControl,
      filePreviewUrl,
      closeForm
    }
  }
}
</script>

<style scoped lang="scss">
.image-viewer__wrapper {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.image-viewer__mask {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  opacity: .9;
  background: #000;
}

.image-viewer__canvas {
  width: 100%;
  height: 100%;
  position: fixed;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding-bottom: 17px;

  img {
    max-height: 100%;
    max-width: 100%;
  }
}

</style>
