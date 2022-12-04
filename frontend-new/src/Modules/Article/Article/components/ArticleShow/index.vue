<template>
  <div>
    <q-card>
      <q-card-section>
        <div class="text-weight-bold  text-h6 ">{{ article.title }}</div>
        <ShowTime :time="article.updated_at" class="text-grey-8 text-small-80"/>
      <q-separator/>
      </q-card-section>

      <q-card-section>
        <div v-html="article.text" class="q-pa-md"></div>
        <div class="q-pa-sm">
          <ShowArticleFiles v-if="article.uid" :article-uid="article.uid"  />
        </div>
      </q-card-section>
      <q-card-section>
        <CommentsBlock v-if="article && article.allow_comments" type="article" :object-uid="article.uid" />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import ShowArticleFiles from 'src/Modules/Article/Article/components/ShowArticleFiles/index.vue'
import CommentsBlock from 'src/Modules/Comments/components/CommentsBlock/index.vue'
export default defineComponent({
  components: {
    ShowTime,
    ShowArticleFiles,
    CommentsBlock
  },
  props: {
    article: {
      type: Object,
      required: true
    }
  },
  setup () {
    return {
    }
  }
})
</script>

<style scoped>

</style>
