<template>
  <div>
    <div>
      <q-btn icon="add" outline color="primary" label="Создать" @click="openDialogAction" />
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
                show-description
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
            <div class="q-pa-md">
              <AddFileBtn @add-files="addFile" multiple parent-type="appeal" :parent-uid="data.uid" />
              <FilesListShow v-model="data.files" edit />
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
import AddFileBtn from 'src/Modules/Files/components/AddFileBtn/index.vue'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import { uid } from 'quasar'

export default defineComponent({
  components: {
    AppealTypeSelect,
    FilesListShow,
    AddFileBtn
  },
  props: {
    userUid: {
      type: String,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref({
      uid: uid(),
      title: '',
      text: '',
      files: [],
      appeal_type_id: 1
    })
    const dialogVisible = ref(null)
    const router = useRouter()
    const route = useRoute()
    const saveData = () => {
      createAppeal(data.value)
        .then(res => {
          data.value.files.forEach(item => {
            item.sendFileToServer()
          })
          dialogVisible.value = false
          emit('success')
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
    }
    const addFile = (ar) => {
      ar.forEach(val => {
        data.value.files.push(val)
        // val.sendFileToServer()
      })
    }
    const openDialogAction = () => {
      data.value.uid = uid();
      data.value.title = '';
      data.value.text = '';
      data.value.files = [];
      data.value.appeal_type_id = 1;
      dialogVisible.value = true
    }
    onMounted(() => {

    })
    return {
      openDialogAction,
      data,
      addFile,
      saveData,
      dialogVisible
    }
  }
})
</script>

<style scoped>

</style>
