<template>
  <div>
    <q-card>
      <q-card-section>
        <div>
          <div class="text-weight-bold  text-h6 ">{{ article.title }}</div>
          <ShowTime :time="article.updated_at" class="text-grey-8 text-small-80"/>
        </div>
        <q-separator />
      </q-card-section>
      <q-card-section>
        <div v-html="article.resume" class="q-pa-md"/>
      </q-card-section>
      <q-card-actions>
        <div class="row items-center justify-between w-100 q-px-md">
          <div class="row items-center">
            <div class="q-mr-sm">
              <q-btn color="primary" flat dense no-caps label="Подробнее" @click="showArticle"/>
            </div>
            <div v-if="article.allow_comments === 1">
              <el-button type="primary" size="small" plain icon="el-icon-chat-dot-square" @click="showArticle">?</el-button>
            </div>
          </div>
        </div>
      </q-card-actions>
    </q-card>
  </div>
</template>

<script>
import ShowTime from 'src/components/ShowTime/index.vue'
export default {
  components: {
    ShowTime
  },
  props: {
    article: {
      type: Object,
      required: true
    }
  },
  methods: {
    showArticle () {
      this.$router.push('/article/show/' + this.article.uid)
    }
  }
}
</script>

<style scoped>

</style>
