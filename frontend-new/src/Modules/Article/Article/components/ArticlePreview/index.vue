<template>
  <div>
    <q-card>
      <q-card-section>
        <div class="relative-position">
          <div class="article-preview-title">{{ article.title }}</div>
          <div class="row items-center q-col-gutter-md text-grey-8 text-small-80">
            <div v-if="article.author">
              {{ article.author.name }}
            </div>
            <ShowTime :time="article.updated_at" from />
          </div>
          <div v-if="authStore.checkPermission('article-edit') " class="absolute-top-right desktop-only">
            <q-btn icon="settings" color="primary" flat :to="'/admin/article/edit/' + article.id" />
          </div>
        </div>
        <q-separator />
      </q-card-section>
      <q-card-section>
        <div v-html="article.resume" class="overflow-hidden article-preview" style="" />
      </q-card-section>
      <q-card-actions>
        <div class="row items-center q-col-gutter-md">
          <div>
            <q-btn
              color="primary"
              outline
              label="Подробнее"
              @click="showArticle('')"
            />
          </div>
          <div v-if="article.allow_comments > 1 || article.count_comments > 0" @click="showArticle('#comments')">
            <q-btn
              color="grey-2"
              text-color="primary"
              icon="message"
              :label="article.count_comments || ''"
              unelevated
            />
          </div>
        </div>
      </q-card-actions>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useRouter } from 'vue-router'
import ShowTime from 'src/components/ShowTime/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ShowTime
  },
  props: {
    article: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const authStore = useAuthStore()
    const showArticle = (val) => {
      router.push({ path: '/article/show/' + props.article.slug, hash: val })
    }
    return {
      data,
      showArticle,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
<style lang="scss">
.article-preview-title {
  font-size: 1.15rem;
  font-weight: 600;
  line-height: 1.5rem;
  //letter-spacing: 0.0125em;
}

.article-preview {
  & img {
    max-height: 100px;
    width: auto;
  }
}

</style>