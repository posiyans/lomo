<template>
  <div class="wrapper-vue-image-crop-upload">
    <div>
      <q-btn label="Сменить иконку сайта" color="primary" @click="imageCropperShow = true" />
    </div>
    <div>
      <image-cropper
        v-model="imageCropperShow"
        :width="128"
        :height="128"
        with-credentials
        :headers="headers"
        imgFormat="png"
        field="favicon"
        :url="url"
        no-circle
        lang-type="ru"
        @update:model-value="close"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ImageCropper from 'vue-image-crop-upload'
import { Cookies } from 'quasar'

export default defineComponent({
  components: {
    ImageCropper
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const imageCropperShow = ref(false)
    const headers = computed(() => {
      return {
        'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN'),
        Accept: 'application/json'
      }
    })
    const url = process.env.BASE_API + '/api/v2/setting/favicon/update'
    const close = () => {
      imageCropperShow.value = false
    }
    return {
      data,
      imageCropperShow,
      headers,
      close,
      url
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
