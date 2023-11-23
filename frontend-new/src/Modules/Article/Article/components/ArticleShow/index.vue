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
      <div v-if="article.public !== 1">
        <q-btn outline color="negative">
          <StatusShow :model-value="article.public" />

        </q-btn>
      </div>
      <div v-if="authStore.checkPermission('article-edit') " class="">
        <q-btn icon="settings" color="primary" flat :to="'/admin/article/edit/' + article.id" />
      </div>
    </q-card-section>
    <q-separator />
    <q-card-section>
      <div v-html="article.text" class="q-px-sm"></div>
      <div>
        <div v-if="article.files.length > 0" class="text-weight-bold">Файлы:</div>
        <FilesListShow :model-value="article.files" />
      </div>
      <div>
        <div v-if="article.allow_comments === 1" class="q-mt-md">
          <q-btn
            color="grey-2"
            text-color="primary"
            icon="message"
            :label="messageBlock.messageList.value.length"
            unelevated
          />
        </div>
      </div>
    </q-card-section>
    <q-separator />
    <MessageBlock :message-block="messageBlock" class="q-pa-md comments-block" />
    <SendCommentBlock :message-block="messageBlock" class="bg-grey-3" />
  </q-card>

</template>

<script>
import { defineComponent } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import FilesListShow from 'src/Modules/Files/components/FilesListShow/index.vue'
import SendCommentBlock from 'src/Modules/Comments/components/SendCommentBlock/index.vue'
import MessageBlock from 'src/Modules/Comments/components/MessageBlock/index.vue'
import { useMessageBlock } from 'src/Modules/Comments/hooks/useMessageBlock'
import StatusShow from 'src/Modules/Article/Article/components/StatusShow/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    FilesListShow,
    SendCommentBlock,
    MessageBlock,
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
    const messageBlock = useMessageBlock('article', props.article.uid)
    return {
      messageBlock,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
