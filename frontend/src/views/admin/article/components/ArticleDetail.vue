<template>
  <div class="createPost-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" :label-position="labelPosition" class="form-container">
      <div class="pt1 createPost-main-container" style="padding-top: 0; padding-bottom: 0">
        <div style="display: inline-block;">
          <OnMain v-model="postForm.news" />
        </div>
        <div style="display: inline-block; ">
          <CategoryDropdown v-model="postForm.category_id" />
        </div>
        <div style="display: inline-block;">
          <CommentDropdown v-model="postForm.allow_comments" />
        </div>
        <div style="display: inline-block;">
          <el-button v-loading="loading" type="success" @click="submitForm">
            Опубликовать
          </el-button>
        </div>
        <div style="display: inline-block;">
          <el-button v-loading="loading" type="warning" @click="draftForm">
            Черновик
          </el-button>
        </div>
      </div>
      <div class="createPost-main-container">
        <el-row>
          <el-col :span="24">
            <el-form-item style="margin-bottom: 40px;" prop="title">
              <MDinput v-model="postForm.title" :maxlength="100" name="name" required>
                Заголовок
              </MDinput>
            </el-form-item>

            <div class="postInfo-container">
              <el-row>
                <el-col :xs="24" :sm="10" justify="start">
                  <el-form-item label-width="150px" label="Время публикации:" class="postInfo-container-item">
                    <el-date-picker v-model="displayTime" type="datetime" :picker-options="{ firstDayOfWeek: 1}" format="HH:mm dd-MM-yyyy" placeholder="Выберите дату и время" />
                  </el-form-item>
                </el-col>
                <el-col :xs="24" :sm="10">
                  <el-form-item style="" label-width="70px" label="UID:">
                    <el-input v-model="postForm.uid" :rows="1" show-word-limit maxlength="50" autosize placeholder="Пожалуйста, введите содержание или оставте пустым" />
                  </el-form-item>
                </el-col>
              </el-row>
            </div>
          </el-col>
        </el-row>

        <el-form-item style="margin-bottom: 40px;" label-width="70px" label="Резюме:">
          <el-input v-model="postForm.resume " :rows="1" show-word-limit maxlength="250" type="textarea" class="article-textarea" autosize placeholder="Пожалуйста, введите содержание или оставте пустым" />
        </el-form-item>

        <el-form-item prop="content" style="margin-bottom: 30px;">
          <Tinymce :id="postForm.uid" ref="editor" v-model="postForm.text" :height="400" />
        </el-form-item>

        <el-form-item prop="image_uri" style="margin-bottom: 30px;">
          <Upload :id="postForm.uid" v-model="postForm.files" />
        </el-form-item>
        {{ postForm.attached_files }}
      </div>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce'
// import Upload from '@/components/Upload/SingleImage3'
import Upload from './upload/index'
import MDinput from '@/components/MDinput'
import { fetchArticle, createArticle, updateArticle } from '@/api/article'
import { searchUser } from '@/api/remote-search'
import { CommentDropdown, OnMain } from './Dropdown'
import CategoryDropdown from './Dropdown/Category'
import { mapState } from 'vuex'

const defaultForm = {
  public: false,
  title: '', // 文章题目
  text: '', // 文章内容
  resume: '', // 文章摘要
  files: [],
  news: 1,
  display_time: +new Date(),
  id: undefined,
  category_id: null,
  allow_comments: 0,
  uid: ''
}

export default {
  name: 'ArticleDetail',
  components: { Tinymce, MDinput, Upload, CommentDropdown, CategoryDropdown, OnMain },
  props: {
    isEdit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        this.$message({
          message: rule.field + ' Обязательное поле',
          type: 'error'
        })
        callback(new Error(' Обязательное поле'))
      } else {
        callback()
      }
    }
    return {
      datetime: +new Date(),
      postForm: Object.assign({}, defaultForm),
      loading: false,
      userListOptions: [],
      rules: {
        // image_uri: [{ validator: validateRequire }],
        title: [{ validator: validateRequire }],
        text: [{ validator: validateRequire }]
      },
      tempRoute: {}
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    }),
    labelPosition() {
      return this.device === 'desktop' ? 'left' : 'top'
    },
    contentShortLength() {
      return this.postForm.resume.length
    },
    displayTime: {
      // set and get is useful when the data
      // returned by the back end api is different from the front end
      // back end return => "2013-06-25 06:59:25"
      // front end need timestamp => 1372114765000
      get() {
        return (+new Date(this.datetime))
      },
      set(val) {
        this.datetime = new Date(val)
      }
    }
  },
  created() {
    this.postForm.uid = this.create_UUID()
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id
      this.fetchData(id)
    }

    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    // https://github.com/PanJiaChen/vue-element-admin/issues/1221
    this.tempRoute = Object.assign({}, this.$route)
  },
  methods: {
    create_UUID() {
      var dt = new Date().getTime()
      var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (dt + Math.random() * 16) % 16 | 0
        dt = Math.floor(dt / 16)
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16)
      })
      return uuid
    },
    fetchData(id) {
      fetchArticle(id).then(response => {
        this.postForm = response.data
        // this.postForm.allow_comments = Boolean(response.data.allow_comments)
        this.datetime = this.$moment(this.postForm.publish_time)
        this.setTagsViewTitle()

        // set page title
        this.setPageTitle()
      }).catch(err => {
        console.log(err)
      })
      return null
    },
    setTagsViewTitle() {
      const title = 'Статья id'
      const route = Object.assign({}, this.tempRoute, { title: `${title}-${this.postForm.id}` })
      this.$store.dispatch('tagsView/updateVisitedView', route)
    },
    setPageTitle() {
      const title = 'Статья id'
      document.title = `${title} - ${this.postForm.id}`
      return null
    },
    submitForm() {
      this.postForm.public = true
      this.saveForm()
    },
    saveForm() {
      this.$refs.postForm.validate(valid => {
        if (valid) {
          this.loading = true
          // this.postForm.public = true
          this.postForm.display_time = this.$moment(this.datetime).format('YYYY-MM-DD HH:mm:ss')
          if (this.isEdit) {
            const id = this.$route.params && this.$route.params.id
            updateArticle(id, this.postForm)
              .then(response => {
                this.postForm = response.data
                // this.postForm.allow_comments = Boolean(response.data.allow_comments)
                this.datetime = this.$moment(this.postForm.publish_time)
              })
          } else {
            createArticle(this.postForm)
              .then(response => {
                this.$router.push({ name: 'AdminEditArticle', params: { id: response.data.id }})
              })
          }
          if (this.postForm.public) {
            this.$notify({
              title: 'успех',
              message: 'Статья успешно опубликована',
              type: 'success',
              duration: 2000
            })
          } else {
            this.$notify({
              title: 'успех',
              message: 'Черновик сохранен',
              type: 'success',
              duration: 2000
            })
          }

          this.loading = false
        } else {
          return false
        }
      })
    },
    draftForm() {
      this.postForm.public = false
      this.saveForm()
    },
    getRemoteUserList(query) {
      searchUser(query).then(response => {
        if (!response.data.items) return
        this.userListOptions = response.data.items.map(v => v.name)
      })
    }
  }
}
</script>

<style scoped>
  .createPost-main-container {
    padding: 40px 45px 20px 50px;
  }
  .createPost-container {
    position: relative;
    .postInfo-container {
      position: relative;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
      }
    }
    .word-counter {
      width: 100px;
      position: absolute;
      right: 10px;
      top: 0px;
    }
  }

  .article-textarea /deep/ {
    textarea {
      padding-right: 40px;
      resize: none;
      border: none;
      border-radius: 0px;
      border-bottom: 1px solid #bfcbd9;
    }
  }

  @media screen and (max-width: 700px) {
    .createPost-main-container {
      padding: 40px 6px 20px 6px;

      .postInfo-container {
        position: relative;
        margin-bottom: 10px;

        .postInfo-container-item {
          float: left;
        }
      }
    }
  }
</style>
