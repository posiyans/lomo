<template>
  <div>
    <q-ajax-bar
      ref="bar"
      position="top"
      color="info"
      size="3px"
    />
    <div class="row items-center bg-grey-2 q-col-gutter-sm q-pa-sm">
      <div class="filter-item">
        <ArticleCategorySelect
          v-model="articleStore.article.category_id"
          outlined
          set-default
          dense
        />
      </div>
      <div class="filter-item">
        <StatusSelect
          v-model="articleStore.article.status"
          label="Статус"
          outlined
          dense
        />
      </div>
      <div class="filter-item">
        <SelectCommentEnable v-model="articleStore.article.allow_comments" />
      </div>
      <div>
        <q-btn no-caps no-wrap color="primary" :loading="savingData" :label="btnLabel" @click="saveArticle" />
      </div>
      <q-space />
      <div v-if="articleStore.article?.user" class="row items-center bg-white br4">
        <router-link :to="`/admin/user/show/${articleStore.article?.user?.uid}`" class="user-link">
          <UserAvatarByUid :uid="articleStore.article?.user?.uid" size="24px" />
          {{ articleStore.article?.user?.name }}
        </router-link>
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
    <div class="relative-position">
      <Tinymce
        :key="articleStore.key"
        v-model="articleStore.article.text"
        :parent-uid="articleStore.article.uid"
        :height="300" />
    </div>
    <div class="q-pa-md">
      <AddFileBtn @add-files="addFile" multiple parent-type="article" :parent-uid="articleStore.article.uid" />
      <FilesListShow v-model="articleStore.files" edit get-url />
    </div>
  </div>
</template>

<script>
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
import { errorMessage } from 'src/utils/message'

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
  props: {},
  setup() {
    const bar = ref(null)
    const showMoreSetting = ref(false)
    const $q = useQuasar()
    const articleStore = useArticleStore()
    const router = useRouter()
    const savingData = ref(false)
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
      savingData.value = true
      const barRef = bar.value
      barRef.start()
      articleStore.saveArticle()
        .then(res => {
          if (editArticle.value) {
            $q.notify('Статья успешно сохранена')
            articleStore.getData()
              .finally(() => {
                barRef.stop()
              })
          } else {
            $q.notify('Статья успешно добавлена')
            router.push('/admin/article/edit/' + res.data.id)
          }
        })
        .catch(er => {
          errorMessage(er.response.data.errors)
        })
        .finally(() => {
          barRef.stop()
          savingData.value = false
        })
    }

    return {
      showMoreSetting,
      bar,
      addFile,
      articleStore,
      savingData,
      btnLabel,
      saveArticle
    }
  }
})
</script>

<style scoped lang="scss">
.user-link {
  &:hover {
    opacity: .8;
  }
}
</style>
