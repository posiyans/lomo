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
      <div class="row">
        <div>
          <q-btn label="Отмена" flat color="negative" />
          <q-btn :label="buttonTitle" type="submit" color="primary" />
        </div>
        <q-space />
        <div>
          <q-btn label="Удалить" color="negative" @click="deleteCameraAction" />
        </div>
      </div>
    </q-form>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { addCamera, deleteCamera, updateCamera } from 'src/Modules/Camera/api/camera.js'
import { required } from 'src/utils/validators'
import InputNumber from 'components/Input/InputNumber/index.vue'
import { useQuasar } from 'quasar'

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
            .then(res => {
              emit('success')
            })
        }
      }
      func(data.value)
        .then(res => {
          emit('success')
        })

    }
    const $q = useQuasar()
    const deleteCameraAction = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Подтвердите удаление камеры',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'primary'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Удалить',
          color: 'negative'
        },
        persistent: true
      }).onOk(() => {
        deleteCamera(props.camera.id)
          .then(() => {
            emit('success')
          })
      })

    }
    onMounted(() => {
      data.value = props.camera
    })
    return {
      buttonTitle,
      required,
      deleteCameraAction,
      data,
      saveData
    }
  }
})
</script>

<style scoped>

</style>
