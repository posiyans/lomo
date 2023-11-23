<template>
  <div>
    <div @click="showDialog">
      <slot>
        <q-btn color="primary" label="Добавить" />
      </slot>
    </div>
    <q-dialog
      v-model="showAddForm"
      full-width
      full-height
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">{{ dialogTitle }}</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <EditCameraForm :camera="camera" @success="closeDialog" />
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { computed, defineComponent, onMounted, ref } from 'vue'
import EditCameraForm from 'src/Modules/Camera/components/EditCameraForm/index.vue'

export default defineComponent({
  components: {
    EditCameraForm
  },
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
    const data = ref(null)
    const showAddForm = ref(false)
    const edit = computed(() => {
      if (props.camera.id) {
        return true
      }
      return false
    })
    const dialogTitle = computed(() => {
      if (edit.value) {
        return 'Редактировать камеру'
      }
      return 'Добавить камеру'
    })

    const showDialog = () => {
      showAddForm.value = true
    }
    const closeDialog = () => {
      showAddForm.value = false
      emit('reload')
    }
    onMounted(() => {

    })
    return {
      closeDialog,
      dialogTitle,
      showAddForm,
      showDialog,
      data
    }
  }
})
</script>

<style scoped>

</style>
