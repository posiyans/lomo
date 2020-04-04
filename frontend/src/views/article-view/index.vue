<template>
  <div>
    <el-card>
      <div class="article-preview-header">
        <h2>{{ article.title }}</h2>
      </div>
      <div class="article-preview-body" >
        <span v-html="article.text"/>
      </div>
      <div v-if="article.files.length > 0">
        <div class="file-list-header">Файлы:</div>
        <ul>
          <li v-for="file in article.files">{{ file.name }}
            <span class="file-size">{{ file.size | sizeFilter }}</span>
            <el-link :href="file.id | urlFilter " type="success">Скачать</el-link>
          </li>
        </ul>
      </div>

      <div class="article-preview-footer">
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="12"><div style="padding-left: 20px;"><el-button type="primary" @click="back">Назад</el-button></div></el-col>
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
    },
    filters: {
      urlFilter(val){
        return process.env.VUE_APP_BASE_API + '/user/storage/file/' + val
      },
      sizeFilter(size){
        if (size) {
          const i = Math.floor(Math.log(size) / Math.log(1024));
          return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
        }
        return '';
      }
    },
    mounted() {
      this.fetchArticle()
      console.log(this.$route.params.id)
    },
    data() {
      return {
        article: {}
      }
    },
    methods: {
      back(){
        this.$router.back()
      },
      fetchArticle(){
        console.log('fetch article')
        console.log(this.id)
        fetchUserArticle(this.$route.params.id)
          .then(response => {
            console.log(response)
            this.article = response.data
          })
      }
    }
  }
</script>

<style scoped>
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
  .article-preview-body {
    text-indent: 1.5em; /* Отступ первой строки */
    text-align: justify; /* Выравнивание по ширине */
  }

  .article-preview-footer {

  }
</style>

<style>
  .file-size {
    padding-left: 10px;
    color: #999;
  }
  .file-list-header {
    font-weight: bold;
    color: #1f2d3d;
  }
  /*.leftimg {*/
  /*  float:left; !* Выравнивание по левому краю *!*/
  /*  margin: 7px 20px 20px 0; !* Отступы вокруг картинки *!*/
  /*}*/
  /*.rightimg  {*/
  /*  float: right; !* Выравнивание по правому краю  *!*/
  /*  margin: 7px 0 7px 7px; !* Отступы вокруг картинки *!*/
  /*}*/
</style>
