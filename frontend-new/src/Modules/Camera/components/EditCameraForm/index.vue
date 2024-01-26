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
      <InputNumber
        outlined
        v-model="data.ttl"
        label="TTL"
        lazy-rules
        dense
        hint="Время обновления картинки, сек"
        :rules="[required]"
        style="max-width: 250px;"
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
import { addCamera, updateCamera } from 'src/Modules/Camera/api/camera.js'
import { required } from 'src/utils/validators'
import InputNumber from 'components/Input/InputNumber/index.vue'

export default defineComponent({
  components: {
    InputNumber
  },
  props: {
    camera: {
      type: Object,
      default: () => {
        return {
          access: 'all',
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
      let func = addCamera
      if (edit.value) {
        func = (data) => {
          updateCamera(props.camera.id, data)
        }
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
