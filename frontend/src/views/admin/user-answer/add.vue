<template>
  <div style="padding-left: 25px;">
    <h2>Добавить голос собственника</h2>
    <h3>Тета голосования: {{voting.title}}</h3>
    <div>Укажите участок:</div>
    <UserSteadFind :stead_id="queryStead" @selectStead="setStead"/>
    <div v-if="stead">
      <div class="pt2 pl4 pb4" style="font-size: 1.5em; background-color: #fff; font-weight: bold;">Участок: {{ stead.number }}</div>
      <ShowBulletin  :voting="voting" :key="key" :readonly="false" @setAnswer="setAnswer"/>
      <div class="mt4 mb4">
        <el-upload
          v-if="stead"
          class="upload-demo"
          ref="upload"
          :action="url"
          :on-success="sendOk"
          :before-upload="test"
          :on-change="handleChange"
          with-credentials
          :data="req_data"
          :headers="token"
          :auto-upload="false">
          <el-button slot="trigger" size="small" type="primary">Прикрепить бюллетень</el-button>
          <div class="el-upload__tip" slot="tip">jpg, png, pdf файлы размером не более 3MБ</div>
        </el-upload>
      </div>
      <div class="mb6">
        <el-button type="primary" @click="submitUpload">Сохранить</el-button>
      </div>
    </div>
  </div>
</template>

<script>
import ShowBulletin from '@/components/ShowBulletin'
import { fetchAdminVoting, addUserAswers } from '@/api/admin/voting/'
import UserSteadFind from '@/components/UserSteadFind'

export default {
  components: {
    ShowBulletin,
    UserSteadFind
  },
  data() {
    return {
      id: '',
      queryStead: '',
      key: 1,
      voting: {},
      userAnswer: {},
      file: [],
      stead: '',
      token: { 'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN') }
    }
  },
  computed: {
    req_data() {
      return {
        voting: this.id,
        stead: this.stead.id,
        answers: this.userAnswer
      }
    },
    url() {
      return process.env.VUE_APP_BASE_API + '/api/v1/admin/voting/owner/add-answer'
    },
    steadTitle() {
      if (this.stead) {
        return 'для участка № ' + this.stead.number
      }
      return ''
    }
  },
  created() {
    this.id = this.$route.params && this.$route.params.id
    if (this.$route.query.stead) {
      this.queryStead = +this.$route.query.stead
    }
    this.getVoting()
  },
  methods: {
    handleChange(file, fileList) {
      console.log(file)
      this.file = fileList
    },
    test() {
      console.log('test!!!')
    },
    getCookie(name) {
      const matches = document.cookie.match(new RegExp(
        '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'
      ))
      return matches ? decodeURIComponent(matches[1]) : undefined
    },
    sendOk(val) {
      // this.$message(val)
      console.log(val)
    },
    validForm() {
      let valid = true
      this.voting.questions.forEach(item => {
        if (!(item.id in this.userAnswer)) {
          valid = false
        }
      })
      console.log('valid')
      console.log(valid)
      if (!valid) {
        this.$message.error('Указаны не все ответы!')
      }
      return valid
    },
    submitUpload() {
      console.log('-----')
      if (this.validForm()) {
        if (this.file.length > 0) {
          this.$refs.upload.submit()
        } else {
          addUserAswers(this.req_data)
            .then(response => {
              if (response.data.status) {
                this.$message('Данные успешно сохранены')
                // this. = response.data.data
              } else {
                this.$message.error('Ошибка сохранения')
                this.$message.error(response.data.data)
              }
            })
        }
      }
    },
    setStead(val) {
      this.stead = val
      this.key++
    },
    setAnswer(val) {
      this.userAnswer = val
    },
    getVoting() {
      console.log('getvoting')
      fetchAdminVoting(this.id)
        .then(response => {
          this.voting = response.data.data
          // this.loading = false
        })
    }
  }

}
</script>

<style scoped>

</style>
