<template>
  <div>
    <EditArticleForm />
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">
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
        <StatusSelect v-model="postForm.public" />
      </div>
      <div class="createPost-main-container">
        <el-row>
          <el-col :span="24">
            <el-form-item style="margin-bottom: 40px;" prop="title">
              <q-input v-model="postForm.title" :maxlength="100" no-caps label="Заголовок" />

            </el-form-item>

            <div class="postInfo-container">
              <el-row>
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
import Upload from './components/upload/index'
import { fetchArticle, createArticle, updateArticle } from 'src/Modules/Article/Article/api/article.js'
import CommentDropdown from './components/Comment/index.vue'
import OnMain from './components/OnMain/index.vue'

import CategoryDropdown from 'src/Modules/Article/Category/components/ArticleCategorySelect/index.vue'
import StatusSelect from 'src/Modules/Article/Article/components/StatusSelect/index.vue'
import { uid } from 'quasar'
import EditArticleForm from 'src/Modules/Article/Article/components/EditArticleForm/index.vue'

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
  components: {
    Upload,
    CommentDropdown,
    CategoryDropdown,
    OnMain,
    EditArticleForm,
    StatusSelect
  },
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
      rules: {
        // image_uri: [{ validator: validateRequire }],
        title: [{ validator: validateRequire }],
        text: [{ validator: validateRequire }]
      },
      tempRoute: {}
    }
  },
  computed: {
    contentShortLength() {
      return this.postForm.resume.length
    },
    displayTime: {
      get() {
        return (+new Date(this.datetime))
      },
      set(val) {
        this.datetime = new Date(val)
      }
    }
  },
  created() {
    this.postForm.uid = uid()
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id
      this.fetchData(id)
    }
    this.tempRoute = Object.assign({}, this.$route)
  },
  methods: {
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
      return false
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
                this.$router.push({ name: 'AdminEditArticle', params: { id: response.data.id } })
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

.article-textarea >>> {
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
