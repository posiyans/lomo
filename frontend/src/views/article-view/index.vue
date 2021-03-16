<template>
  <div>
    <el-card
      v-loading="loading"
      class="card-mobile"
    >
      <div class="article-preview-header">
        <h2>{{ article.title }}</h2>
        <div v-if="editor" class="article-setting-icon" @click="editArticle">
          <i class="el-icon-s-tools" />
        </div>
      </div>
      <div class="article-preview-body">
        <span v-html="article.text" />
      </div>
      <div v-if="article.files && article.files.length > 0">
        <div class="file-list-header">Файлы:</div>
        <ul>
          <li v-for="file in article.files" :key="file.id">{{ file.name }}
            <span class="file-size">{{ file.size | sizeFilter }}</span>
            <el-link :href="file.id | urlFilter " type="success">Скачать</el-link>
          </li>
        </ul>
      </div>

      <div class="article-preview-footer">
        <el-divider class="divider-footer" />
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="14">
            <span style="padding-left: 20px;">
              <el-button type="primary" size="mini" @click="back">Назад</el-button>
            </span>
            <span style="padding-left: 20px;">
              <el-button v-if="article.allow_comments==1" type="primary" size="mini" plain icon="el-icon-chat-dot-square">{{ article.comments_show.length }}</el-button>
            </span>
          </el-col>
          <el-col :span="10"><div style="text-align: right; padding: 10px 20px 0px 0;color: #848484; height: 100%;">{{ publicTime(article.publish_time) }}</div></el-col>
        </el-row>
      </div>
      <div v-if="article.allow_comments==1" class="comments-body">
        <Comments v-model="article" />
      </div>
    </el-card>
  </div>
</template>

<script>
import { fetchUserArticle } from '@/api/article'
import Comments from '@/components/Comments/index.vue'
export default {
  components: { Comments },
  filters: {
    urlFilter(val) {
      return process.env.VUE_APP_BASE_API + '/api/user/storage/file/' + val
    },
    sizeFilter(size) {
      if (size) {
        const i = Math.floor(Math.log(size) / Math.log(1024))
        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
      }
      return ''
    }
  },
  props: {
    id: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      loading: true,
      article: {}
    }
  },
  computed: {
    editor() {
      if (this.$store.getters.user.allPermissions.includes('create-article')) {
        return true
      }
      return false
    }
  },
  mounted() {
    this.fetchArticle()
  },
  methods: {
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
    editArticle() {
      if (this.editor) {
        this.$router.push('/admin-article/edit/' + this.article.id)
      }
    },
    back() {
      this.$router.back()
    },
    fetchArticle() {
      fetchUserArticle(this.$route.params.id)
        .then(response => {
          this.article = response.data
          this.loading = false
        })
    }
  }
}
</script>

<style scoped>
  .article-preview-header {
    padding: 0 20px;
    border-bottom: 1px solid #e6ebf5;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #303133;
    position: relative;
  }
  .article-preview-body {
    padding: 20px 0;
    text-indent: 1.5em; /* Отступ первой строки */
    text-align: justify; /* Выравнивание по ширине */
  }
  .comments-body {
    border-radius: 5px;
    border: solid 1px #cecece;
    margin-top: 10px;
  }
.divider-footer {
  margin: 10px 0;
}
  @media screen and (max-width: 480px) {

    .article-preview-header{
      padding: 0 20px;
      border-bottom: 1px solid #e6ebf5;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      color: #303133;
      position: relative;
    }
  }
</style>

<style>
  .file-size {
    padding: 0 10px;
    color: #999;
  }
  .file-list-header {
    font-weight: bold;
    color: #1f2d3d;
  }

  .article-preview-body > img {
    width: 90%;
  }
  .article-preview-body {
    overflow-x: auto;
    text-indent: 1.5em; /* Отступ первой строки */
    text-align: justify; /* Выравнивание по ширине */
  }
  @media screen and (max-width: 480px) {
    .article-preview-body{
      padding: 5px 5px;
    }
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
