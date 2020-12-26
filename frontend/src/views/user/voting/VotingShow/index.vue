<template>
  <div>
    <el-card
      v-if="voting"
      v-loading="loading"
      class="card-mobile"
    >
      <div class="article-preview-header">
        <div class="header">
          {{ VotingTypeText }}
          <span>
            <el-tag :type="voting.status | statusColorFilter">
              {{ voting.status | statusFilter }}
            </el-tag>
          </span>
        </div>

        <div v-if="editor" class="article-setting-icon" @click="editArticle">
          <i class="el-icon-s-tools" />
        </div>
      </div>
      <div class="article-preview-body">
        <h2>{{ voting.title }}</h2>
        <div v-if="voting.type='owner'" class="blue">
          Дата голосования с {{ voting.date_start | moment('DD-MM-YYYY') }} по {{ voting.date_stop | moment('DD-MM-YYYY') }}
        </div>
        <span v-html="voting.description" />
      </div>
      <div v-if="voting.files && voting.files.length > 0">
        <div class="file-list-header">Файлы:</div>
        <ul>
          <li v-for="file in voting.files" :key="file.id">{{ file.name }}
            <span class="file-size">{{ file.size | sizeFilter }}</span>
            <el-link :href="file.id | urlFilter " type="success">Скачать</el-link>
          </li>
        </ul>
      </div>
      <QuestionShow :voting="voting" @changeResult="fetchVoting" />
      <div class="article-preview-footer">
        <el-divider class="divider-footer" />
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="14">
            <Back />
            <span v-if="false" style="padding-left: 20px;">
              <el-button v-if="voting.comments==1" type="primary" size="mini" plain icon="el-icon-chat-dot-square">{{ voting.comments.length }}</el-button>
            </span>
          </el-col>
          <el-col :span="10"><div style="text-align: right; padding: 10px 20px 0px 0;color: #848484; height: 100%;">{{ publicTime(voting.date_publish) }}</div></el-col>
        </el-row>
      </div>
      <!--      <div v-if="false && voting.comments==1"  class="comments-body">-->
      <!--        <Comments v-model="voting" />-->
      <!--      </div>-->
    </el-card>
    <el-card
      v-else
      v-loading="loading"
      class="card-mobile"
      style="min-height: 200px"
    >
      <div class="no-found">
        Голосование не найдено
      </div>
    </el-card>
  </div>
</template>

<script>
import { fetchUserVoting } from '@/api/user/voting'
import QuestionShow from './QuestionShow/index.vue'
import Back from '@/components/Back'

const selectStatusOptions = [
  { key: 'new', display_name: 'Новое' },
  { key: 'execution', display_name: 'Идет' },
  { key: 'done', display_name: 'Законченно' },
  { key: 'cancel', display_name: 'Отмененное' }
]

const Status = selectStatusOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})
export default {
  components: { QuestionShow, Back },
  filters: {
    statusColorFilter(status) {
      const color = {
        new: 'info',
        execution: '',
        done: 'success',
        cancel: 'danger'
      }
      return color[status]
    },
    urlFilter(val) {
      return process.env.VUE_APP_BASE_API + '/api/user/storage/file/' + val
    },
    statusFilter(status) {
      return Status[status]
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
      voting: false
    }
  },
  computed: {
    VotingTypeText() {
      if (this.voting.type === 'public') {
        return 'Открытое голосование'
      }
      return 'Голосование собственников'
    },
    editor() {
      if (this.$store.getters.user.allPermissions.includes('сreate-polls')) {
        return true
      }
      return false
    }
  },
  mounted() {
    this.fetchVoting()
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
      fetchUserVoting(this.$route.params.id)
        .then(response => {
          if (response.data.status) {
            this.voting = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
          this.loading = false
        })
    }
  }
}
</script>

<style scoped>
  .header {
    margin: 10px 0;
    font-size: 14px;
    font-weight: bold;
  }
  .header span {
    margin-left: 10px;
  }

  .article-preview-header {
    padding: 0 20px;
    border-bottom: 1px solid #e6ebf5;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #303133;
    /*margin-top: -17px;*/
    position: relative;
  }

  .article-preview-header h1 {
    font-size: 2vmax;
    margin-top: 10px;
    margin-bottom: 10px;
  }
  .article-preview-body{
    padding: 20px 0;
  }
  .article-preview-body {
    text-indent: 1.5em; /* Отступ первой строки */
    text-align: justify; /* Выравнивание по ширине */
  }
  .article-preview-body h2 {
    margin: 0px 0 10px;
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
