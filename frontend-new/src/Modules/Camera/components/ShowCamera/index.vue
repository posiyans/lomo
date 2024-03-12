<template>
  <div class="q-pa-md">
    <div v-if="showName" class="text-weight-bolder q-pa-sm">
      {{ item.name }}
    </div>
    <div :class="{ 'cursor-pointer': showAccess }" @click="showFull">
      <q-img
        :src="cameraUrl"
        spinner-color="white"
        style="height: 140px; max-width: 250px"
      >
        <template v-slot:error>
          <div class="absolute-full flex flex-center bg-grey-2 text-black">
            Не удается загрузить изображение
          </div>
        </template>
      </q-img>
    </div>
    <ShowImageFull v-if="fullImageVisible" :src-img="cameraUrl" :name="item.name" @close="fullImageVisible = false" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import ShowImageFull from 'src/Modules/Camera/components/ShowImageFull/index.vue'

export default defineComponent({
  components: {
    ShowImageFull
  },
  props: {
    item: Object,
    showName: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const fullImageVisible = ref(false)
    const showFull = () => {
      if (showAccess.value) {
        fullImageVisible.value = true
      }
    }
    const time = new Date().getTime()
    const authStore = useAuthStore()
    const showAccess = computed(() => {
      return authStore.checkPermission(['camera-show'])
    })
    const cameraUrl = computed(() => {
      return process.env.BASE_API + '/api/v2/camera/get-image/' + props.item.id + '?s=' + time + '.jpg'
    })
    return {
      cameraUrl,
      showAccess,
      showFull,
      fullImageVisible
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
