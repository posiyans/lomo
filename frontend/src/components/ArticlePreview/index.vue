<template>
  <div class="resume-ps-card">
    <el-card class="resume-el-card">
      <div class="resume-article-preview-header">
        <h2>{{ article.title }}</h2>
        <div v-if="editor" class="article-setting-icon" @click="editArticle">
          <i class="el-icon-s-tools"></i>
        </div>
      </div>
      <div class="resume-article-preview-body" >
        <p><span v-html="resume"/></p>
      </div>
      <div class="resume-article-preview-footer">
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="24">
              <span class="resume-article-preview-more">
                <el-button type="primary" size="mini" plain @click="showArticle">Подробнее</el-button>
              </span>
              <span class="resume-article-preview-more">
                <el-button v-if="article.allow_comments==1" type="primary" size="mini" plain icon="el-icon-chat-dot-square" @click="showArticle">{{ article.comments.length }}</el-button>
              </span>
            <div class="resume-time-publish">{{publicTime(article.publish_time) }}</div>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
import { fetchUserArticle } from "@/api/article"
export default {
  props: {
    id: {
      type: Number,
      default: 0
    },
    data: Object,
    default: {}
  },
  filters: {
    publishFilter(val) {
      console.log('this')
      console.log(this)
      return moment(val).format('MMMM Do YYYY, h:mm:ss a')
    }
  },
  mounted() {
    if (this.id > 0) {
      this.fetchArticle()
    } else {
      // console.log('props ddata')
      this.article = this.data
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
      if (this.$store.getters.user.allPermissions.includes('create-article')) {
        return true
      }
      return false
    },
    resume() {
      if (this.article.resume) {
        return this.article.resume
      }
      const size = 190
      const newsText = this.article.text.split('</p>')
      return newsText[0] + '</p>'
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
        return this.$moment(val).format('DD MMM YYYY в HH:mm')
      }
      if (time > 24 * 3600 * 2) {
        return this.$moment(val).format('DD MMM в HH:mm')
      }
      return this.$moment(val).fromNow()
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
    top: 11px;
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
    padding: 10px 20px 0px 5px;
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
  .resume-article-preview-body{
    padding: 20px 0;
  }
  .resume-article-preview-footer {

  }
  .resume-article-preview-body >>> img{
    width: 100px;
    float:left; /* Выравнивание по левому краю */
    margin: 0px 20px 0 0; /* Отступы вокруг картинки */
  }
  .article-preview-more {
    padding-left: 20px;
  }
  @media screen and (max-width: 480px) {
    .article-setting-icon{
      top: 5px;
      right: 20px;
    }
    .resume-article-preview-body{
      padding: 10px 5px;
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
