/* eslint-disable */
<template>
  <div class="q-pa-md">
    <q-ajax-bar
      ref="bar"
      position="top"
      color="info"
      size="3px"
    />
    <div class="row items-center bg-grey-2 q-col-gutter-sm q-pb-sm q-px-sm">
      <div class="filter-item">
        <q-checkbox
          v-model="articleStore.article.news"
          label="Показывать в списке"
          :true-value="1"
          :false-value="0"
        />
      </div>
      <div class="filter-item">
        <ArticleCategorySelect
          v-model="articleStore.article.category_id"
          outlined
          dense
        />
      </div>
      <div class="filter-item">
        <StatusSelect
          v-model="articleStore.article.public"
          label="Статус"
          outlined
          dense
        />
      </div>
      <div class="filter-item">
        <SelectCommentEnable v-model="articleStore.article.allow_comments" />
      </div>
      <div>
        <q-btn no-caps no-wrap color="primary" :label="btnLabel" @click="saveArticle" />
      </div>
      <q-space />
      <div>
        <UserAvatarByUid :uid="articleStore.article?.user?.uid" />
      </div>
      <div>
        <router-link :to="`/admin/user/show/${articleStore.article?.user?.uid}`">
          {{ articleStore.article?.user?.name }}
        </router-link>
      </div>
      <div>
        <AddBanUserBtn :user-uid="articleStore.article?.user?.uid" type="article" object-uid="all">
          <q-btn icon="delete" flat round color="negative" />
        </AddBanUserBtn>
      </div>
      <div>
        <q-btn no-caps no-wrap color="info" label="Смотреть" :to="'/article/show/' + articleStore.article.slug" />
      </div>
      <div>
        <q-btn color="black" icon="more_horiz" flat @click="showMoreSetting = !showMoreSetting" />
      </div>
    </div>
    <div v-if="showMoreSetting" class="row items-center bg-grey-2 q-col-gutter-sm q-pb-sm q-px-sm">
      <div style="min-width: 250px;">
        <q-input
          v-model="articleStore.article.slug"
          outlined
          dense
          no-caps
          bg-color="white"
          label="Адрес страницы"
          :hint='`/article/show/${articleStore.article.slug}`'
        />
      </div>
    </div>
    <div class="q-py-sm">
      <q-input v-model="articleStore.article.title" outlined :maxlength="100" no-caps label="Заголовок" />
    </div>
    <div class="q-py-sm">
      <q-input v-model="articleStore.article.resume" :maxlength="100" no-caps label="Резюме" />
    </div>
    <div>
      <Tinymce v-model="articleStore.article.text" :height="300" />
    </div>
    <div class="q-pa-md">
      <AddFileBtn @add-files="addFile" multiple parent-type="article" :parent-uid="articleStore.article.uid" />
      <FilesListShow v-model="articleStore.files" edit />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import ArticleCategorySelect from 'src/Modules/Article/Category/components/ArticleCategorySelect/index.vue'
import SelectCommentEnable from 'src/Modules/Comments/components/SelectCommentEnable/index.vue'
import Tinymce from 'src/components/Tinymce'
import { useArticleStore } from 'src/Modules/Article/Article/stores/useArticleStore'
import StatusSelect from 'src/Modules/Article/Article/components/StatusSelect/index.vue'
import { useRouter } from 'vue-router'
import AddFileBtn from 'src/Modules/Files/components/AddFileBtn/index.vue'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import AddBanUserBtn from 'src/Modules/BanUsers/components/AddBanUserBtn/index.vue'

export default defineComponent({
  components: {
    ArticleCategorySelect,
    AddBanUserBtn,
    FilesListShow,
    SelectCommentEnable,
    Tinymce,
    AddFileBtn,
    UserAvatarByUid,
    StatusSelect
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup() {
    const bar = ref(null)
    const showMoreSetting = ref(false)
    const $q = useQuasar()
    const articleStore = useArticleStore()
    const router = useRouter()
    const editArticle = computed(() => {
      return !!articleStore.id
    })
    const btnLabel = computed(() => {
      return editArticle.value ? 'Сохранить' : 'Добавить'
    })
    const addFile = (ar) => {
      ar.forEach(val => {
        articleStore.files.push(val)
        val.sendFileToServer()
      })
    }
    const saveArticle = () => {
      const barRef = bar.value
      barRef.start()
      articleStore.saveArticle()
        .then((data) => {
          if (editArticle.value) {
            $q.notify('Статья успешно сохранена')
            articleStore.getData()
              .finally(() => {
                barRef.stop()
              })
          } else {
            $q.notify('Статья успешно добавлена')
            router.push('/admin/article/edit/' + data.id)
            barRef.stop()
          }
        })
        .catch(er => {
          $q.notify({
            message: 'Ошибка сохранения статьи',
            color: 'negative'
          })
        })
    }
    return {
      showMoreSetting,
      bar,
      addFile,
      articleStore,
      btnLabel,
      saveArticle,
    }
  }
})
</script>

<style scoped>

</style>
