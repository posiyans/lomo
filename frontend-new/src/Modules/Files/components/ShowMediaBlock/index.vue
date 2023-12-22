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
        <img :src="srcImg" alt="">
        <div class="absolute-bottom text-teal text-center row items-center justify-center bg-grey-4 q-py-xs">
          <div class="q-mr-md">
            {{ fileName }}
          </div>
          <DownloadFileBtn :url-file="srcImg" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { onUnmounted, ref } from 'vue'
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
    fileName: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    // const $q = useQuasar()
    // onMounted(() => {
    //   $q.fullscreen.toggle()
    // })
    onUnmounted(() => {
      // $q.fullscreen.exit()
    })
    const closeMedia = () => {
      // $q.fullscreen.exit()
    }
    const hideControl = ref(true)
    const closeForm = () => {
      emit('close')
    }
    return {
      hideControl,
      closeForm,
      closeMedia
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
