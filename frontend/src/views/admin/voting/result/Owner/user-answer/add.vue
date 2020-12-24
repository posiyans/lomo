<template>
  <div style="padding-left: 25px;">
    <h2>Добавить голос собственника</h2>
    <h3>Тета голосования: {{ voting.title }}</h3>
    <div>Укажите участок:</div>
    <UserSteadFind :stead_id="queryStead" @selectStead="setStead" />
    <div v-if="stead">
      <div class="pt2 pl4 pb4" style="font-size: 1.5em; background-color: #fff; font-weight: bold;">Участок: {{ stead.number }}</div>
      <div v-if="uploadShow" class="mt4 mb4">
        <el-upload
          v-if="stead"
          ref="upload"
          class="upload-demo"
          :action="url_file"
          :on-success="sendOk"
          :on-change="handleChange"
          with-credentials
          :data="req_data"
          :headers="token"
          :auto-upload="true"
        >
          <el-button slot="trigger" size="small" type="primary">Прикрепить бюллетень</el-button>
          <div slot="tip" class="el-upload__tip">jpg, png, pdf файлы размером не более 3MБ</div>
        </el-upload>
      </div>
      <div v-if="!uploadShow">
        <el-image
          style="width: 100px; height: 100px"
          :src="file.url | urlFilter "
          fit="contain"
          :preview-src-list="file_array"
        >

        </el-image>
        <ShowBulletin :key="key" :voting="voting" :readonly="false" @setAnswer="setAnswer" />
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
  filters: {
    urlFilter(val) {
      return process.env.VUE_APP_BASE_API + val + '&type=jpg'
    }
  },
  components: {
    ShowBulletin,
    UserSteadFind
  },
  data() {
    return {
      uploadShow: true,
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
    file_array() {
      return [process.env.VUE_APP_BASE_API + this.file.url + '&type=jpg']
    },
    req_data() {
      return {
        voting: this.id,
        stead: this.stead.id,
        answers: this.userAnswer
      }
    },
    url_file() {
      return process.env.VUE_APP_BASE_API + '/api/v1/admin/voting/owner/upload-file'
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
      console.log('получили участок')
      this.queryStead = +this.$route.query.stead
    }
    this.getVoting()
  },
  methods: {
    handleChange(file, fileList) {
      console.log(file)
      // this.file = fileList
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
      if (val.status && val.file.url) {
        this.file = val.file
        this.uploadShow = false
      }
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
      if (this.validForm()) {
        addUserAswers(this.req_data)
          .then(response => {
            if (response.data.status) {
              this.$router.push('/admin/voting/result/' + this.id)
              this.$message.success('Данные успешно сохранены')
            } else {
              this.$message.error(response.data.data)
            }
          })
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
