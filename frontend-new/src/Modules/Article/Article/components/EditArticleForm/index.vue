/* eslint-disable */
<template>
  <div class="q-pa-md">
    <div class="row items-center  bg-grey-4 q-col-gutter-sm q-pa-sm">
      <div style="min-width: 220px;">
        <q-checkbox
            v-model="EditArticleStore.article.news"
            label="Показывать в списке"
            :true-value="1"
            :false-value="0"
        />
      </div>
      <div style="min-width: 220px;">
        <ArticleCategorySelect v-model="EditArticleStore.article.category_id" />
      </div>
      <div style="min-width: 220px;">
        <SelectCommentEnable v-model="EditArticleStore.article.allow_comments" />
      </div>
      <div>
        <q-btn no-caps no-wrap color="secondary" label="Сохранить" />
      </div>
    </div>
    <div class="q-py-sm">
      <q-input v-model="EditArticleStore.article.title" outlined :maxlength="100" no-caps label="Заголовок" />
    </div>
    <div class="q-py-sm">
      <q-input v-model="EditArticleStore.article.resume" :maxlength="100" no-caps label="Резюме" />
    </div>
    <div>
      <Tinymce :id="uidToken" v-model="EditArticleStore.article.text" :height="400" />
    </div>
    <div>
      <UploadForm :files="EditArticleStore.article.files" />
    </div>
    -{{ EditArticleStore.article }}-
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { uid, useQuasar } from 'quasar'
import { useEditArticleStore } from './store/store'
import ArticleCategorySelect from 'src/Modules/Article/Category/components/ArticleCategorySelect/index.vue'
import SelectCommentEnable from 'src/Modules/Comments/components/SelectCommentEnable/index.vue'
import Tinymce from 'src/components/Tinymce'
import UploadForm from 'src/Modules/Files/components/UploadForm/index.vue'

export default defineComponent({
  components: {
    ArticleCategorySelect,
    SelectCommentEnable,
    Tinymce,
    UploadForm
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup() {
    const EditArticleStore = useEditArticleStore()
    const $q = useQuasar()
    const uidToken = ref(uid())
    return {
      EditArticleStore,
      uidToken
    }
  }
})
</script>

<style scoped>

</style>
