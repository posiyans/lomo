<template>
  <div class="main-comments">
    <div v-for="i in value.comments" class="comments-post">
      <el-row>
        <el-col :xs="4" :sm="2" :lg="1" align="middle" ><el-avatar :size="40" :src="i.avatar | avatarUrl"></el-avatar></el-col>
        <el-col :xs="20" :sm="20" :lg="20">
          <div class="comment_author">
            {{i.userName}}
          </div>
          <div class="reply_text">
            <div>
              <div class="wall_reply_text" v-html="$options.filters.marked(i.message)">
              </div>
            </div>
          </div>
          <div class="comment_date">
            {{i.created_at | moment(' DD MMM  в HH:mm')}}
          </div>
          <el-divider class="divider"></el-divider>
        </el-col>
      </el-row>

    </div>
    <el-row v-if="user" class="send" align="middle">
      <el-col :xs="4" :sm="2" :lg="1"><el-avatar :size="40" :src="user.avatar | avatarUrl"></el-avatar></el-col>
      <el-col :xs="16" :sm="18" :lg="19">
        <el-input
          type="textarea"
          autosize
          placeholder="Написать комментарий..."
          v-model="newComment">
        </el-input>
      </el-col>
      <el-col :xs="4" :sm="2" :lg="1">
        <div class="comment-send" @click="sendComment">
          <i class="el-icon-s-promotion"></i>
        </div>
      </el-col>
    </el-row>
    <div v-else class="no-send" align="middle">
      <router-link to="/login/index">Чтобы оставить комментарий, необходимо авторизоваться</router-link>
    </div>
  </div>
</template>

<script>
import { addComment, fetchListComments } from '@/api/user/comment.js'
// var marked = require('marked')

export default {
  props: {
     value: {
       // type: Array,
       // defaults: []
     }
  },
  filters: {
    mFilter(val) {
      return 'dasdasdasd' + val
    },
    avatarUrl(val){
      if ( val[0] === '/') {
        return process.env.VUE_APP_BASE_API + val
      }
      return val
    }
  },
  data() {
    return {
      newComment: '',
      items: []
    }
  },
  computed: {
    user(){
      if (this.$store.getters.user.allPermissions.includes('guest')) {
         return false
      }
      return this.$store.getters.user
    }
  },
  created() {
    // this.getComments()
  },
  methods:{

    getComments(){
      // const data = {
      //   article_id: this.value,
      // }
      // fetchListComments(data)
      //   .then(response => {
      //     this.items = response.data.data
      //   })
    },
    sendComment() {
      const data = {
        article_id: this.value.id,
        message: this.newComment
      }
      addComment(data).then(response => {
        if (response.data.status) {
          this.value.comments.push(response.data.data)
        } else {
          this.$message({
            message: 'Не удалось добавить комментарий',
            type: 'error'
          })
        }
      })
      this.newComment = ''
    }
  }
}
</script>

<style scoped>
  .main-comments {
    margin-top: 10px;
    margin-left: 20px;
  }
  @media (max-width: 770px) {
    .main-comments {
      margin-left: 0px;
    }
  }

  .comment_title {
    margin-bottom: 10px;
    padding-left: 30px;
  }
  .comment_date {
    margin-top: 5px;
    color: #939393;
    cursor: pointer;
    direction: ltr;
    font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, "Noto Sans Armenian", "Noto Sans Bengali", "Noto Sans Cherokee", "Noto Sans Devanagari", "Noto Sans Ethiopic", "Noto Sans Georgian", "Noto Sans Hebrew", "Noto Sans Kannada", "Noto Sans Khmer", "Noto Sans Lao", "Noto Sans Osmanya", "Noto Sans Tamil", "Noto Sans Telugu", "Noto Sans Thai", sans-serif;
    font-size: 12.5px;
    font-weight: 400;
    line-height: 14px;
    overflow-wrap: break-word;
    text-align: left;
  }
  .divider {
    margin: 5px 0;
  }
  .comment_author {
    color: #2a5885;
    cursor: pointer;
    direction: ltr;
    font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, "Noto Sans Armenian", "Noto Sans Bengali", "Noto Sans Cherokee", "Noto Sans Devanagari", "Noto Sans Ethiopic", "Noto Sans Georgian", "Noto Sans Hebrew", "Noto Sans Kannada", "Noto Sans Khmer", "Noto Sans Lao", "Noto Sans Osmanya", "Noto Sans Tamil", "Noto Sans Telugu", "Noto Sans Thai", sans-serif;
    font-size: 12.5px;
    font-weight: 500;
    line-height: 17px;
    overflow-wrap: break-word;
    text-align: left;
  }
  .send {
    margin-top: 5px;
  }
  .no-send {
    margin-top: 15px;
    margin-left: 50px;
    margin-bottom: 15px;
    color: #1b3958;
  }
  .comment-send > i {
    cursor: pointer;
    color: #606266;
    margin: 0 20px;
    font-size: 2em;
    vertical-align: middle;
  }
</style>
<style>
  .wall_reply_text > p {
    margin-top: 5px;
    margin-bottom: 5px;
  }
  .wall_reply_text > h1 {
    margin-top: 5px;
    margin-bottom: 5px;
  }
</style>
