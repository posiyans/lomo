<template>
  <div>
    <q-card>
      <q-card-section>
        <div class="relative-position">
          <div class="text-weight-bold text-h6">{{ article.title }}</div>
          <ShowTime :time="article.updated_at" format="DD-MM-YYYY HH:mm" class="text-grey-8 text-small-80" />
          <div v-if="authStore.checkPermission('article-edit') " class="absolute-top-right">
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
              @click="showArticle"
            />
          </div>
          <div v-if="article.allow_comments > 1 " @click="showArticle">
            <CountMessageBlock type="article" :uid="article.uid" />
          </div>
        </div>
      </q-card-actions>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ShowTime from 'src/components/ShowTime/index.vue'
import CountMessageBlock from 'src/Modules/Comments/components/CountMessageBlock/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    ShowTime,
    CountMessageBlock
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
    const route = useRoute()
    const authStore = useAuthStore()
    const showArticle = () => {
      router.push('/article/show/' + props.article.slug)
    }
    onMounted(() => {

    })
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
.article-preview {
  & img {
    max-height: 100px;
    width: auto;
  }
}

</style>