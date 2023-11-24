<template>
  <q-card>
    <q-card-section>
      <div style="min-height: 100%">
        <div class="row items-center q-col-gutter-md">
          <div class="text-weight-bold" style="font-size: 1.5rem;">
            Предложить новость
          </div>
          <div class="filter-item">
            <ArticleCategorySelect v-model="article.category_id" label="В раздел" outlined dense only-public set-default />
          </div>
        </div>
      </div>
    </q-card-section>
    <q-card-section>
      <div class="q-mb-md" style="width: 100%;">
        <q-input
          v-model="article.title"
          no-caps
          outlined
          label="Заголовок"
        />
      </div>
      <TextEditor v-model="article.text" add-image parent-type="article" :parent-uid="article.uid" />
      <div class="q-pa-sm">
        <AddFileBtn @add-files="addFiles" multiple :disabled="article.files.length >= maxFiles" :max-size="maxSize * 1024 * 1024" parent-type="article" :parent-uid="article.uid" />
        <small> До {{ maxFiles }} файлов, размером до {{ maxSize }} Мб</small>
      </div>
      <FilesListShow v-model="article.files" edit />
      <div class="q-pl-sm q-pt-lg">
        <q-btn color="primary" label="Сохранить" @click="showDialog" />
        <q-dialog
          title="Внимание"
          v-model="dialogVisible"
          width="30%"
        >
          <q-card>
            <q-card-section class="row items-center q-pb-none">
              <div class="text-h6">Сохранить?</div>
              <q-space />
              <q-btn icon="close" flat round dense v-close-popup />
            </q-card-section>

            <q-card-section>
              <div class="q-pa-md">
                <div>
                  Сохранить новость и отправить ее на модерацию?
                </div>
                <div>После успешной модерации статья будет размещена на сайте.</div>
              </div>
            </q-card-section>
            <q-card-section>
              <div class="row items-center justify-end q-col-gutter-md">
                <div>
                  <q-btn label="Отмена" flat color="negative" v-close-popup />
                </div>
                <div>
                  <q-btn label="Сохранить" color="primary" @click="saveArticle" />
                </div>
              </div>
            </q-card-section>
          </q-card>
        </q-dialog>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, reactive, ref } from 'vue'
import AddFileBtn from 'src/Modules/Files/components/AddFileBtn/index.vue'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import { uid, useQuasar } from 'quasar'
import ArticleCategorySelect from 'src/Modules/Article/Category/components/ArticleCategorySelect/index.vue'
import { userAddArticle } from 'src/Modules/Article/Article/api/article'
import TextEditor from 'components/TextEditor/index.vue'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {
    AddFileBtn,
    FilesListShow,
    TextEditor,
    ArticleCategorySelect
  },
  props: {},
  setup(props, { emit }) {
    const $q = useQuasar()
    const data = ref(null)
    const dialogVisible = ref(false)
    const maxFiles = 10
    const maxSize = 5
    const article = reactive({
      uid: uid(),
      title: '',
      category_id: '',
      text: '',
      files: []
    })
    const countUploadsFile = computed(() => {
      let i = 0
      article.files.forEach(item => {
        if (item.upload.success) {
          i++
        }
      })
      return i
    })
    const cancel = () => {
      emit('close')
    }
    const showDialog = () => {
      if (article.title === '') {
        $q.dialog({
          title: 'Внимание',
          message: 'Укажите заголовок для записи'
        })
      } else if (article.text === '') {
        $q.dialog({
          title: 'Внимание',
          message: 'Текст записи не может быть пустым'
        })
      } else {
        dialogVisible.value = true
      }
    }
    const addFiles = (ar) => {
      ar.forEach(val => {
        if (article.files.length < maxFiles) {
          article.files.push(val)
          val.sendFileToServer()
        }
      })
    }
    const saveArticle = () => {
      const data = {
        title: article.title,
        text: article.text,
        category_id: article.category_id,
        uid: article.uid
      }
      userAddArticle(data)
        .then(res => {
          // saveFiles()
          dialogVisible.value = false
          $q.notify({
            message: 'Данные успешно отправлены, после проверки она появится на сайте',
            color: 'secondary'
          })
          emit('success')
        })
        .catch(er => {
          if (er.response.status === 403) {
            errorMessage('Вам запрещено предлагать записи')
          } else {
            errorMessage(er.response.data.errors)
          }
        })
    }


    return {
      saveArticle,
      dialogVisible,
      showDialog,
      maxFiles,
      maxSize,
      article,
      addFiles,
      data,
      cancel
    }
  }
})
</script>

<style scoped>

</style>
