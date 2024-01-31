<template>
  <div>
    <div @click="imagecropperShow=true">
      <slot>
        <div class="relative-position" :style="'width:' + size">
          <UserAvatarByUid v-if="authStore.user.uid" :uid="authStore.user.uid" :size="size" />
          <div class="absolute-top-right bg-grey q-pa-xs o-80 avatar-edit-btn">
            <q-btn dense icon="edit" size="sm" color="white" flat />
          </div>
        </div>
      </slot>
    </div>
    <image-cropper
      v-model="imagecropperShow"
      :width="400"
      :height="400"
      with-credentials
      :headers="headers"
      imgFormat="jpg"
      field="avatar"
      :url="url"
      lang-type="ru"
      @update:model-value="close"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ImageCropper from 'vue-image-crop-upload'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import { Cookies } from 'quasar'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ImageCropper,
    UserAvatarByUid
  },
  props: {
    size: {
      type: String,
      default: '150px'
    }
  },
  setup() {
    const imagecropperShow = ref(null)
    const headers = computed(() => {
      return {
        'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN'),
        Accept: 'application/json'
      }
    })
    const url = process.env.BASE_API + '/api/v2/avatar/user/upload'
    const authStore = useAuthStore()
    const close = () => {
      imagecropperShow.value = false
      authStore.key++
    }

    return {
      close,
      imagecropperShow,
      url,
      authStore,
      headers
    }
  }
})
</script>

<style scoped>

</style>
