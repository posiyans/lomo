<template>
  <div class="createPost-container">
    <el-form ref="postForm" v-loading="loadingForm" :model="postForm" :rules="rules" class="form-container">
      <div class="pt1 createPost-main-container" style="padding-top: 0; padding-bottom: 0">
        <div style="display: inline-block;">
          <TypeDropdown v-model="postForm.type"/>
        </div>
        <div style="display: inline-block;">
          <el-button v-loading="loading" type="warning" @click="draftForm">
            Сохранить
          </el-button>
        </div>
      </div>
      <div class="createPost-main-container">
        <h1>{{ typeTitle }}</h1>
        <el-row>
          <el-col :span="24">
            <el-form-item style="margin-bottom: 40px;" prop="title">
              <MDinput v-model="postForm.title" :maxlength="100" name="name" required>
                Тема голосования
              </MDinput>
            </el-form-item>

            <div class="postInfo-container">
              <el-row v-if="postForm.type === 'owner'">
                <el-col :span="8" justify="start">
                  <el-form-item label-width="150px"  label="Время публикации:" class="postInfo-container-item">
                    <el-date-picker v-model="publishTime" type="datetime" :clearable="false" :picker-options="datePickerOptions" :firstDayOfWeek="2" format="HH:mm dd-MM-yyyy" placeholder="Выберите дату и время" />
                  </el-form-item>
                </el-col>
                <el-col :span="8">
                  <el-form-item label-width="160px"  label="Голосование с :" class="postInfo-container-item" required prop="dateStart">
                    <el-date-picker
                      v-model="dataRange"
                      type="daterange"
                      range-separator="по"
                      :firstDayOfWeek="2"
                      :picker-options="rangePickerOptions"
                      format="dd-MM-yyyy"
                      placeholder="Выберите дату и время"
                    />
                  </el-form-item>
                </el-col>
<!--                <el-col :span="8">-->
<!--                  <el-form-item label-width="160px"  label="Начало голосования:" class="postInfo-container-item">-->
<!--                    <el-date-picker v-model="displayTime" type="datetime" :firstDayOfWeek="2" format="HH:mm dd-MM-yyyy" placeholder="Выберите дату и время" />-->
<!--                  </el-form-item>-->
<!--                </el-col>-->
<!--                <el-col :span="8">-->
<!--                  <el-form-item label-width="150px"  label="Конец голосования:" class="postInfo-container-item">-->
<!--                    <el-date-picker v-model="displayTime" type="datetime" :firstDayOfWeek="2" format="HH:mm dd-MM-yyyy" placeholder="Выберите дату и время" />-->
<!--                  </el-form-item>-->
<!--                </el-col>-->
              </el-row>
            </div>
          </el-col>
        </el-row>

        <div style="padding-left: 5px; padding-bottom: 10px; font-weight: 700;">Подробное описание голосования:</div>

        <el-form-item prop="content" style="margin-bottom: 30px;" >
          <Tinymce ref="editor" v-model="postForm.description" :id="postForm.uid" :height="400" />
        </el-form-item>

        <el-form-item prop="image_uri" style="margin-bottom: 30px;">
          <Upload v-model="postForm.files" :id="postForm.uid" />
        </el-form-item>
        {{postForm.attached_files}}

        <div v-for="(question, i) in postForm.questions" class="question">
          <el-form-item style="margin-bottom: 40px;" label-width="100px" :label="`Вопрос № ${i+1}`">
            <el-input v-model="question.text " :rows="1" show-word-limit maxlength="250" type="textarea" class="article-textarea" autosize placeholder="Введите текс вопроса голосования" />
          </el-form-item>

          <el-form-item v-for="(answer, j) in question.answers" style="margin-bottom: 40px;" label-width="100px" :label="`Ответ № ${j+1}`">
            <el-input v-model="answer.text " :rows="1" show-word-limit maxlength="250" type="textarea" class="article-textarea" autosize placeholder="Введите текс ответа" />
          </el-form-item>
          <el-button type="primary" icon="el-icon-circle-plus" class="answer-button" @click="addAnswer(i)">Добавить ответ</el-button>
          </div>
        <el-button type="success" icon="el-icon-circle-plus" class="question-button" @click="addQuestion">Добавить вопрос голосования</el-button>

      </div>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce'
import Upload from './upload/index'
import MDinput from '@/components/MDinput'
import Sticky from '@/components/Sticky'

import { createVoting, fetchAdminVoting, updateVoting } from '@/api/admin/voting.js'

import { searchUser } from '@/api/remote-search'
import { CommentDropdown } from './Dropdown'
import TypeDropdown from './Dropdown/Type.vue'
const defaultForm = {
  public: false,
  title: '',
  description: '',
  resume: '',
  files: [],
  date_publish: +new Date(),
  date_start: '',
  date_stop: '',
  id: undefined,
  comments: 0,
  uid: '',
  type: 'public',
  questions: [
    {
      text: '',
      answers: [
        {
          text: ''
        },
        {
          text: ''
        }]
    }
  ]
}

export default {
  name: 'ArticleDetail',
  components: { Tinymce, MDinput, Upload, Sticky, CommentDropdown, TypeDropdown },
  props: {
    isEdit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    var vm = this;
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
      options: {
        owner: 'Голосование собственников',
        public: 'Публичное голосование'
      },
      rrr: '',
      datetime: +new Date(),
      postForm: Object.assign({}, defaultForm),
      loadingForm: true,
      loading: false,
      userListOptions: [],
      rules: {
        // image_uri: [{ validator: validateRequire }],
        // title: [{ validator: validateRequire }],
        title: [{ required: true, message: 'Введите тему голосования' }],
        text: [{ validator: validateRequire }],
        dateStart: [{ required: true, message: 'Необходимо установить период голосования!' }],
      },
      datePickerOptions:  {
        disabledDate(time) {
          return time.getTime() < Date.now()
        }
      },
      rangePickerOptions: {
        disabledDate(time) {
          if (vm.postForm.type === 'owner') {
            if (time.getTime() < (vm.publishTime + 14 * 60 * 60 * 24 * 1000)) {
              return true
            }
          }
          return time.getTime() < (Date.now() - 90 * 60 * 24 * 1000)
        }
      },
      answerCount: ['Ответ № 1', 'Ответ № 2']
    }
  },
  computed: {
    votingType() {
      return this.postForm.type
    },
    dataRange: {
      set(val){
        this.postForm.date_start = this.$moment(val[0]).format('YYYY-MM-DD HH:mm:ss')
        this.postForm.date_stop = this.$moment(val[1]).format('YYYY-MM-DD HH:mm:ss')
      },
      get(){
        return [this.$moment(this.postForm.date_start), this.$moment(this.postForm.date_stop)]
      }
    },
    typeTitle(){
      // return this.options.indexOf('')
      return this.options[this.postForm.type]
    },
    contentShortLength() {
      return this.postForm.resume.length
    },
    publishTime: {
      get() {
        return this.$moment(this.postForm.date_publish)
      },
      set(val) {
        this.dataRange = ['','']
        this.postForm.date_publish = this.$moment(val).format('YYYY-MM-DD HH:mm:ss')
      }
    }
  },
  created() {
    this.postForm.uid = this.create_UUID()
    this.postForm.date_publish = this.$moment(this.postForm.date_publish).format('YYYY-MM-DD HH:mm:ss')
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id
      this.fetchData(id)
    } else {
      this.loadingForm = false
    }
  },
  methods: {
    addQuestion(){
      this.postForm.questions.push({text: '', answers: [{text: ''},{text: ''}]})
    },
    disabledDate(time) {
      return time.getTime() > Date.now();
    },
    addAnswer(question) {
      // if (this.answerCount.length > 5){
      //   this.$notify({
      //     title: 'Внимание',
      //     message: 'НЕ много ответов???',
      //     type: 'success',
      //     duration: 2000
      //   })
      // }
      this.postForm.questions[question].answers.push({text: ''})
    },
    create_UUID(){
      var dt = new Date().getTime()
      var uuid = 'xxxxxxxx-xxxx-voting-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (dt + Math.random()*16)%16 | 0
        dt = Math.floor(dt/16)
        return (c == 'x' ? r : (r&0x3 | 0x8)).toString(16)
      })
      return uuid
    },
    fetchData(id) {
      fetchAdminVoting(id).then(response => {
        this.postForm = response.data.data
        // set page title
        this.setPageTitle()
        this.loadingForm = false
      }).catch(err => {
        console.log(err)
      })
    },
    setPageTitle() {
      const title = 'Статья id'
      document.title = `${title} - ${this.postForm.id}`
    },
    submitForm() {
      this.postForm.public = true
      alert('не работает')
      // this.saveForm()
    },
    saveForm() {
      // console.log('this.postForm')
      // console.log(this.postForm)
      this.$refs.postForm.validate(valid => {
        if (valid) {
          this.loading = true
          this.postForm.public = true
          // console.log('this.postForm')
          // console.log(this.postForm)
          // this.postForm.display_time = this.$moment(this.datetime).format('YYYY-MM-DD HH:mm:ss')
          if (this.isEdit) {
            const id = this.$route.params && this.$route.params.id
            updateVoting({ voting: this.postForm }, id)
              .then(response => {
                this.postForm = response.data.data
                // this.postForm.allow_comments = Boolean(response.data.allow_comments)
                // this.datetime = this.$moment(this.postForm.publish_time)
                // console.log(response.data)
              })
          } else {
            createVoting({ voting: this.postForm })
              .then(response => {
                // this.$router.push({ name: 'AdminEditArticle', params: { id:response.data.id } })
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
          // console.log('error submit!!')
          return false
        }
      })
    },
    draftForm() {
      this.postForm.public = false
      // console.log(this.postForm)
      this.saveForm()
    },
    getRemoteUserList(query) {
      searchUser(query).then(response => {
        if (!response.data.items) return
        this.userListOptions = response.data.items.map(v => v.name)
      })
    }
  },
  watch: {
    votingType(val) {
      if (val === 'public') {
        this.postForm.date_start = '0000-01-01 00:00:00'
        this.postForm.date_stop = '9999-01-01 00:00:00'
      } else {
        this.postForm.date_start = ''
        this.postForm.date_stop = ''
      }
    }
  }
}
</script>

<style lang="scss" scoped>
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
  .question {
    /*border: solid 1px #0000cc;*/
    border-radius: 10px;
    background-color: #ffffff;
    padding: 20px 20px;
    margin-bottom: 7px;
  }
  .answer-button {
    margin-left: 50px;
    margin-bottom: 10px
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
