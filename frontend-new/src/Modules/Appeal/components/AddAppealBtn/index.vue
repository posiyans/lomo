<template>
  <div>
    <div>
      <q-btn icon="add" outline color="primary" label="Создать" @click="dialogVisible = true" />
    </div>
    <q-dialog
      v-model="dialogVisible"
      maximized
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Создать обращение</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <div class="q-gutter-md">
            <div>
              <AppealTypeSelect
                v-model="data.appeal_type_id"
                outlined
                label="Категория обращения"
              />
            </div>
            <div>
              <q-input
                v-model="data.title"
                outlined
                label="Тема обращения"
              />
            </div>
            <div>
              <q-input
                v-model="data.text"
                outlined
                type="textarea"
                label="Текст обращения"
              />
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <div>
            <q-btn label="Создать" color="primary" @click="saveData" />
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
import AppealTypeSelect from 'src/Modules/Appeal/components/AppealTypeSelect/index.vue'
import { createAppeal } from 'src/Modules/Appeal/api/appealApi'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {
    AppealTypeSelect
  },
  props: {
    userUid: {
      type: String,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref({
      title: '',
      text: '',
      appeal_type_id: 1
    })
    const dialogVisible = ref(null)
    const router = useRouter()
    const route = useRoute()
    const saveData = () => {
      createAppeal(data.value)
        .then(res => {
          dialogVisible.value = false
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
    }
    onMounted(() => {

    })
    return {
      data,
      saveData,
      dialogVisible
    }
  }
})
</script>

<style scoped>

</style>
