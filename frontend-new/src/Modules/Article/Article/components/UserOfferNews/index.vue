<template>
  <q-card>
    <q-card-section>
      <div style="min-height: 100%">
        <div class="row items-center q-col-gutter-md">
          <div class="text-weight-bold" style="font-size: 1.5rem;">
            Предложить новость
          </div>
          <div class="filter-item">
            <ArticleCategorySelect v-model="newArticleStore.article.category_id" label="В раздел" outlined dense only-public set-default />
          </div>
        </div>
      </div>
    </q-card-section>
    <q-card-section>
      <div class="q-mb-md" style="width: 100%;">
        <q-input
          v-model="newArticleStore.article.title"
          no-caps
          outlined
          label="Заголовок"
        />
      </div>
      <TextEditor v-model.trim="newArticleStore.article.text" add-image parent-type="article" :parent-uid="newArticleStore.article.uid" />
      <div class="q-pa-sm">
        <AddFileBtn @add-files="addFiles" multiple :disabled="newArticleStore.article.files.length >= maxFiles" :max-size="maxSize * 1024 * 1024" parent-type="article"
                    :parent-uid="newArticleStore.article.uid" />
        <small> Максимум {{ maxFiles }} файлов, размером до {{ maxSize }} Мб</small>
      </div>
      <FilesListShow v-model="newArticleStore.article.files" edit class="row q-col-gutter-sm" />
      <div class="q-pl-sm q-pt-lg">
        <q-btn label="Очистить" color="negative" :disable="newArticleStore.loading.upload" flat @click="newArticleStore.clear" />
        <q-btn color="primary" label="Сохранить" @click="showDialog" :loading="newArticleStore.loading.upload" />
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
                  <q-btn label="Сохранить" :loading="newArticleStore.loading.upload" color="primary" @click="saveArticle" />
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
import { defineComponent, ref } from 'vue'
import AddFileBtn from 'src/Modules/Files/components/AddFileBtn/index.vue'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import { useQuasar } from 'quasar'
import ArticleCategorySelect from 'src/Modules/Article/Category/components/ArticleCategorySelect/index.vue'
import TextEditor from 'components/TextEditor/index.vue'
import { useNewArticleStore } from 'src/Modules/Article/Article/stores/useNewArticleStore'

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
    const dialogVisible = ref(false)
    const maxFiles = 10
    const maxSize = 5
    const newArticleStore = useNewArticleStore()
    const cancel = () => {
      emit('close')
    }
    const showDialog = () => {
      if (newArticleStore.article.title === '') {
        $q.dialog({
          title: 'Внимание',
          message: 'Укажите заголовок для записи'
        })
      } else if (newArticleStore.article.text.length < 10) {
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
        if (newArticleStore.article.files.length < maxFiles) {
          newArticleStore.article.files.push(val)
        }
      })
    }
    const articleSuccessSave = () => {
      dialogVisible.value = false
      emit('success')
    }
    const saveArticle = () => {
      newArticleStore.saveArticle(articleSuccessSave)
      dialogVisible.value = false
    }


    return {
      newArticleStore,
      saveArticle,
      dialogVisible,
      showDialog,
      maxFiles,
      maxSize,
      addFiles,
      cancel
    }
  }
})
</script>

<style scoped>

</style>
