<template>
  <div class="resume-ps-card">
    <el-card class="resume-el-card">
      <div class="resume-article-preview-header">
        <h2>{{ article.title }}</h2>
        <div v-if="editor" class="article-setting-icon" @click="editArticle">
          <i class="el-icon-s-tools" />
        </div>
      </div>
      <div class="resume-article-preview-body">
        <p v-html="resume" />
      </div>
      <div class="resume-article-preview-footer">
        <el-row type="flex" class="row-bg" justify="space-between">
          <el-col :span="24">
            <span class="resume-article-preview-more">
              <el-button type="primary" size="mini" plain @click="showArticle">Подробнее</el-button>
            </span>
            <span class="resume-article-preview-more">
              <el-button v-if="article.allow_comments === 1" type="primary" size="mini" plain icon="el-icon-chat-dot-square" @click="showArticle">{{ article.comments.length }}</el-button>
            </span>
            <div class="resume-time-publish">{{ publicTime(article.publish_time) }}</div>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
import { fetchUserArticle } from '@/api/article'
export default {
  props: {
    id: {
      type: Number,
      default: 0
    },
    data: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      article: {
        text: ''
      }
    }
  },
  computed: {
    editor() {
      return !!this.$store.getters.user.allPermissions.includes('create-article')
    },
    resume() {
      if (this.article.resume) {
        return this.article.resume
      }
      const newsText = this.article.text.split('</p>')
      return newsText[0] + '</p>'
    }
  },
  mounted() {
    if (this.id > 0) {
      this.fetchArticle()
    } else {
      this.article = this.data
    }
  },
  methods: {
    editArticle() {
      if (this.editor) {
        this.$router.push('/admin-article/edit/' + this.article.id)
      }
    },
    publicTime(val) {
      const time = (this.$moment() - this.$moment(val)) / 1000
      if (time > 24 * 3600 * 360) {
        return this.$moment(val).format('DD MMM YYYY')
      }
      if (time > 24 * 3600 * 10) {
        return this.$moment(val).format('DD MMM')
      }
      return this.$moment(val).format('DD MMM в HH:mm')
    },
    showArticle() {
      this.$router.push('/article/show/' + this.article.id)
    },
    fetchArticle() {
      fetchUserArticle(this.id)
        .then(response => {
          this.article = response.data
        })
    }
  }
}
</script>

<style scoped>

  .article-setting-icon{
    position: absolute;
    top: 5px;
    right: 24px;
    cursor: pointer;
    color: #1890ff;
  }
  .resume-ps-card{
    padding: 0 5px 10px 5px;
  }
  .resume-ps-card >>> el-card__body {
    padding-bottom: 5px;
  }
  .resume-el-card {
    padding-bottom: 0;
  }
  .resume-time-publish {
    float:right;
    padding: 10px 20px 0 5px;
    color: #848484;
    height: 100%;
  }
  .resume-article-preview-header{
    padding: 0 20px;
    border-bottom: 1px solid #e6ebf5;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #303133;
    position: relative;
  }
  .resume-article-preview-body {
    padding: 10px 0;
    line-height: 1.4;
  }
  .resume-article-preview-body p{
    text-indent: 1.5em;
  }
  .resume-article-preview-footer {

  }
  /*h2 {*/
  /*  margin-right: 20px;*/
  /*}*/
  .resume-article-preview-body >>> img{
    width: 100px;
    float:left; /* Выравнивание по левому краю */
    margin: 0 20px 0 0; /* Отступы вокруг картинки */
  }
  .article-preview-more {
    padding-left: 20px;
  }
  @media screen and (max-width: 480px) {
    .resume-article-preview-header{
      padding: 0 20px;
      border-bottom: 1px solid #e6ebf5;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      color: #303133;
      position: relative;
    }
    .article-setting-icon{
      top: 5px;
      right: 20px;
    }
    .resume-article-preview-body{
      padding: 0 5px;
    }
    .resume-article-preview-body >>> img{
      width: 100px;
      float:left; /* Выравнивание по левому краю */
      margin: 0 0 0 0; /* Отступы вокруг картинки */
    }
    .resume-article-preview-more {
      padding-left: 5px;
    }
  }
  .resume-ps-card >>> el-card__body {
    padding-bottom: 5px;
  }
</style>
