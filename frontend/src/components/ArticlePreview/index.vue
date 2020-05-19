<template>
  <div class="ps-card">
    <el-card>
      <div class="article-preview-header">
        <h2>{{ article.id }}. {{ article.title }}</h2>
      </div>
      <div class="article-preview-body" >
        <span v-html="resume"/>
      </div>
      <div class="article-preview-footer">
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="12">
            <span style="padding-left: 20px;">
              <el-button type="primary" size="mini" plain @click="showArticle">Подробнее</el-button>
            </span>
            <span style="padding-left: 20px;">
              <el-button v-if="article.allow_comments==1" type="primary" size="mini" plain icon="el-icon-chat-dot-square" @click="showArticle">{{ article.comments.length }}</el-button>
            </span>
          </el-col>
          <el-col :span="12"><div style="text-align: right; padding: 10px 20px 0px 0;color: #848484; height: 100%;">{{article.publish_time | moment('HH:mm DD-MM-YYYY')}}</div></el-col>
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
    mounted() {
      if (this.id > 0) {
        this.fetchArticle()
      } else {
        console.log('props ddata')
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
      resume() {
        if (this.article.resume) {
          return this.article.resume
        }
        const size = 190
        const newsText = this.article.text.split('</p>')
        return newsText[0]+ '</p>'
      }
    },
    methods: {
      showArticle(){
        this.$router.push('/article/show/'+this.article.id)

      },
      fetchArticle(){
        console.log('fetch article')
        console.log(this.id)
        fetchUserArticle(this.id)
          .then(response => {
            console.log(response)
            this.article = response.data
          })
      }
    }
  }
</script>

<style scoped>
  .ps-card{
    padding: 0 5px 10px 5px;
  }
  .article-preview-header{
    padding: 0 20px;
    border-bottom: 1px solid #e6ebf5;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #303133;
    margin-top: -17px;
  }
  .article-preview-body{
    padding: 20px 0;
  }
  .article-preview-footer {

  }
  .article-preview-body >>> img{
    width: 100px;
    float:left; /* Выравнивание по левому краю */
    margin: 0px 20px 0 0; /* Отступы вокруг картинки */
  }
</style>
