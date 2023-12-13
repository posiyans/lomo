<template>
  <q-card class="q-mb-lg">
    <q-card-section class="relative-position row items-center q-col-gutter-md">
      <div>
        <div class="text-weight-bold text-h6 overflow-hidden  ">
          {{ article.title }}
        </div>
        <ShowTime :time="article.updated_at" class="text-grey-8 text-small-80" />
      </div>
      <q-space />
      <div v-if="article.status !== 2">
        <q-btn outline color="negative">
          <StatusShow :model-value="article.status" />
        </q-btn>
      </div>
      <div v-if="authStore.checkPermission('article-edit') " class="">
        <q-btn icon="settings" color="primary" flat :to="'/admin/article/edit/' + article.id" />
      </div>
    </q-card-section>
    <q-separator />
    <q-card-section v-if="showBody">
      <div v-html="article.text" class="q-px-sm"></div>
      <div v-if="article.files?.length > 0">
        <div v-if="article.files?.length > 0" class="text-weight-bold">Файлы:</div>
        <FilesListShow :model-value="article.files" />
      </div>
      <ArticleChatBlock :article="article" />
    </q-card-section>
    <q-card-section v-if="!showBody">
      <div v-html="article.resume" class="q-px-sm"></div>
    </q-card-section>
  </q-card>
</template>

<script>
import { computed, defineComponent } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import StatusShow from 'src/Modules/Article/Article/components/StatusShow/index.vue'
import ArticleChatBlock from './components/ArticleChatBlock/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    FilesListShow,
    ArticleChatBlock,
    StatusShow
  },
  props: {
    article: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const authStore = useAuthStore()
    const showBody = computed(() => {
      if (authStore.permissions.includes('owner')) {
        return true
      }
      if (props.article.status === 2) {
        return true
      }
      return false
    })

    return {
      showBody,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
