<template>
  <div>
    <q-btn icon="add" flat color="primary" @click="showDialog" />
    <q-dialog v-model="dialogVisible">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Добавить участок</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <div class="q-gutter-md">
            <div>
              <q-input v-model="data.number" outlined label="Номер участка" />
            </div>
            <div>
              <InputNumber v-model="data.size" outlined label="Размер участка, м^2" />
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <div class="row items-center justify-between">
            <div>
              <q-btn icon="upload_file" flat />
            </div>
            <div>
              <q-btn label="Сохранить" color="primary" @click="saveData" />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import InputNumber from 'components/Input/InputNumber/index.vue'
import { addStead } from 'src/Modules/Stead/api/stead'

export default defineComponent({
  components: {
    InputNumber
  },
  props: {},
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const data = ref({
      number: '',
      size: ''
    })
    const router = useRouter()
    const route = useRoute()
    const saveData = () => {
      addStead(data.value)
        .then(res => {
          dialogVisible.value = false
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.errors)
        })
    }
    const showDialog = () => {
      data.value.number = ''
      data.value.size = 0
      dialogVisible.value = true
    }
    onMounted(() => {

    })
    return {
      data,
      dialogVisible,
      saveData,
      showDialog
    }
  }
})
</script>

<style scoped>

</style>
