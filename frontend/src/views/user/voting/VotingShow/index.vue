<template>
  <div>
    <el-card
      v-loading="loading"
      class="card-mobile"
    >
      <div class="article-preview-header">
        <h2>{{ voting.title }}</h2>
        <div v-if="editor" class="article-setting-icon" @click="editArticle">
          <i class="el-icon-s-tools"></i>
        </div>
      </div>
      <div class="article-preview-body" >
        <span v-html="voting.description"/>
      </div>
      <div v-if="voting.files && voting.files.length > 0">
        <div class="file-list-header">Файлы:</div>
        <ul>
          <li v-for="file in voting.files">{{ file.name }}
            <span class="file-size">{{ file.size | sizeFilter }}</span>
            <el-link :href="file.id | urlFilter " type="success">Скачать</el-link>
          </li>
        </ul>
      </div>
      <QuestionShow :voting="voting" @changeResult="fetchVoting"/>
      <div class="article-preview-footer">
        <el-divider class="divider-footer"></el-divider>
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="14">
            <span style="padding-left: 20px;">
              <el-button type="primary" size="mini"  @click="back">Назад</el-button>
            </span>
            <span v-if="false" style="padding-left: 20px;">
              <el-button v-if="voting.comments==1" type="primary" size="mini" plain icon="el-icon-chat-dot-square">{{ voting.comments.length }}</el-button>
            </span>
          </el-col>
          <el-col :span="10"><div style="text-align: right; padding: 10px 20px 0px 0;color: #848484; height: 100%;">{{publicTime(voting.date_publish)}}</div></el-col>
        </el-row>
      </div>
<!--      <div v-if="false && voting.comments==1"  class="comments-body">-->
<!--        <Comments v-model="voting" />-->
<!--      </div>-->
    </el-card>
  </div>
</template>

<script>
import { fetchUserVoting } from '@/api/user/voting'
import Comments from '@/components/Comments/index.vue'
import QuestionShow from './QuestionShow/index.vue'

export default {
  components: { Comments, QuestionShow },
  props: {
    id: {
      type: Number,
      default: 0
    },
  },
  filters: {
    urlFilter(val) {
      return process.env.VUE_APP_BASE_API + '/user/storage/file/' + val
    },
    sizeFilter(size) {
      if (size) {
        const i = Math.floor(Math.log(size) / Math.log(1024));
        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
      }
      return '';
    }
  },
  mounted() {
    this.fetchVoting()
  },
  data() {
    return {
      loading: true,
      voting: {}
    }
  },
  computed: {
    editor() {
      if (this.$store.getters.user.allPermissions.includes('сreate-polls')) {
        return true
      }
      return false
    },
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
        this.$router.push('/admin/voting/edit/' + this.voting.id)
      }
    },
    back() {
      this.$router.back()
    },
    fetchVoting() {
      fetchAdminVoting(this.$route.params.id)
        .then(response => {
          this.voting = response.data.data
          this.loading = false
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
    /*margin-top: -17px;*/
    position: relative;
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
    padding-left: 10px;
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
