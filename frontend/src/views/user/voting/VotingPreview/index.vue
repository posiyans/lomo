<template>
  <div class="resume-ps-card">
    <el-card class="resume-el-card">
      <div class="resume-article-preview-header" :style="borderStyle">
        <div class="voting-header">
          {{ voting.title }}
          <div class="voting-header-type" :class="{'red': voting.type == 'owner', 'blue': voting.type == 'public'}">
            {{ voting.type | typeFilter }}
          </div>
        </div>
        <div v-if="editor" class="article-setting-icon" @click="toEdit">
          <i class="el-icon-s-tools" />
        </div>
      </div>
      <div class="resume-article-preview-body">
        <p><span v-html="resume" /></p>
      </div>
      <div class="resume-article-preview-footer">
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="24">
            <span class="resume-article-preview-more">
              <el-button type="primary" size="mini" plain @click="show">Подробнее</el-button>
            </span>
            <span class="resume-article-preview-more" style="padding: 0 5px">
              <el-button v-if="voting.type == 'owner'" type="success" size="mini" plain icon="el-icon-s-home">{{ voting.countAnswer }}</el-button>
              <el-button v-else type="success" size="mini" plain icon="el-icon-s-custom">{{ voting.countAnswer }}</el-button>
            </span>
            <el-tag :type="voting.status | statusColorFilter">
              {{ voting.status | statusFilter }}
            </el-tag>
            <span v-if="false" class="resume-article-preview-more">
              <el-button v-if="voting.comments =1" type="primary" size="mini" plain icon="el-icon-chat-dot-square" @click="showArticle">{{ voting.comments.length }}</el-button>
            </span>
            <div class="resume-time-publish">{{ publicTime(voting.date_publish) }}</div>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
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
    typeFilter(val) {
      switch (val) {
        case 'public':
          return 'Публичное голосование'
        case 'owner':
          return 'Голосование собственников'
        default:
          return ''
      }
    },
    statusFilter(status) {
      return Status[status]
    }
  },
  props: {
    voting: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
    }
  },
  computed: {
    borderStyle() {
      if (this.voting.type === 'owner') {
        return 'border-bottom: 1px solid #f00;'
      }
      return ''
    },
    editor() {
      if (this.$store.getters.user.allPermissions.includes('сreate-polls')) {
        return true
      }
      return false
    },
    resume() {
      if (this.voting && this.voting.description) {
        const newsText = this.voting.description.split('</p>')
        if (newsText.length > 2) {
          newsText[0] = newsText[0] + '<span style="color: #6a6a6a"> ...</span>'
        }
        return newsText[0] + '</p>'
      }
      return ''
    }
  },
  mounted() {
  },
  methods: {
    toEdit() {
      if (this.editor) {
        this.$router.push('/admin/voting/edit/' + this.voting.id)
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
    show() {
      this.$router.push('/voting/show/' + this.voting.id)
    }
  }
}
</script>

<style scoped>
  .voting-header {
    box-sizing: border-box;
    font-size: 21px;
    line-height: 24.15px;
    margin: 18px 0 0;
    font-weight: bold;
  }
  .voting-header-type {
    font-size: 0.5em;
    letter-spacing: 2px;

    /*color: #003784;*/
  }
  .article-setting-icon{
    position: absolute;
    top: 11px;
    right: 24px;
    cursor: pointer;
    color: #1890ff;
  }
  .resume-ps-card{
    padding: 0;
    padding-bottom: 5px;
  }
  .resume-ps-card >>> el-card__body {
    padding-bottom: 5px;
  }
  .resume-el-card {
    padding-bottom: 0;
  }
  .resume-time-publish {
    float:right;
    padding: 10px 10px 0 0;
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
  /*h2 {*/
  /*  margin-right: 20px;*/
  /*}*/
  .resume-article-preview-body >>> img{
    width: 100px;
    float:left; /* Выравнивание по левому краю */
    margin: 0px 20px 0 0; /* Отступы вокруг картинки */
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
