<template>
  <div>
    <q-form
      @submit="saveData"
      class="q-gutter-md"
    >
      <q-input
        outlined
        v-model="data.name"
        label="Название"
        hint="что за камера"
        lazy-rules
        :rules="[required]"
      />
      <q-input
        outlined
        v-model="data.url"
        label="Путь до rtsp потока"
        lazy-rules
        hint="rtsp://user:password@10.10.10.10:1234/ip01/1"
        :rules="[required]"
      />
      <q-input
        outlined
        v-model="data.ttl"
        label="TTL"
        type="number"
        lazy-rules
        hint="Время обновления картинки, сек"
        :rules="[required]"
      />

      <div>
        <q-btn :label="buttonTitle" type="submit" color="primary" />
      </div>
    </q-form>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { addCamera, updateCamera } from 'src/Modules/Camera/api/camera-admin-api'
import { required } from 'src/utils/validators'

export default defineComponent({
  components: {},
  props: {
    camera: {
      type: Object,
      default: () => {
        return {
          name: '',
          url: '',
          ttl: 2190
        }
      }
    }
  },
  setup(props, { emit }) {
    const data = ref({})
    const router = useRouter()
    const route = useRoute()
    const edit = computed(() => {
      if (props.camera.id) {
        return true
      }
      return false
    })
    const buttonTitle = computed(() => {
      if (edit.value) {
        return 'Сохранить'
      }
      return 'Добавить'
    })
    const saveData = () => {
      console.log('dddd')
      let func = addCamera
      if (edit.value) {
        func = updateCamera
      }
      func(data.value)
        .then(res => {
          emit('success')
        })

    }
    onMounted(() => {
      data.value = props.camera
    })
    return {
      buttonTitle,
      required,
      data,
      saveData
    }
  }
})
</script>

<style scoped>

</style>
