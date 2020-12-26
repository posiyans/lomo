<template>
  <div style="padding-left: 25px;">
    <div class="header"> Добавить голос собственника</div>
    <div class="voting-title">Тема голосования: {{ voting.title }}</div>
    <div class="voting-block">
      <div v-if="editSteadNumber">
        Укажите участок:
        <UserSteadFind :stead_id="queryStead" @selectStead="setStead" />
      </div>
      <div v-if="!editSteadNumber" class="stead-number">
        <!--        <div class="stead-setting">-->
        <!--          <i class="el-icon-edit"></i>-->
        <!--        </div>-->
        Участок: <span>{{ stead.number }} </span>
      </div>
      <div v-if="stead">
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
            <!--            <div slot="tip" class="el-upload__tip">jpg, png, pdf файлы размером не более 3MБ</div>-->
            <div slot="tip" class="el-upload__tip">jpg, png, pdf файлы </div>
          </el-upload>
        </div>
        <div v-if="!uploadShow">
          <div class="image">
            <el-image
              style="width: 100px; height: 100px"
              :src="file.url | urlFilter "
              fit="contain"
              :preview-src-list="file_array"
            />
          </div>
          <ShowBulletin :key="key" :voting="voting" :readonly="false" @setAnswer="setAnswer" />
        </div>
        <div class="mb6">
          <el-button :type="type" @click="submitUpload">Сохранить</el-button>
        </div>
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
      editSteadNumber: true,
      uploadShow: true,
      type: 'danger',
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
        this.type = 'danger'
      } else {
        this.type = 'success'
      }
      return valid
    },
    submitUpload() {
      if (this.validForm()) {
        this.$confirm('Вы точно хотите сохранить результаты голосования?', 'Внимание!', {
          confirmButtonText: 'Да',
          cancelButtonText: 'Нет',
          type: 'warning'
        }).then(() => {
          addUserAswers(this.req_data)
            .then(response => {
              if (response.data.status) {
                this.$router.push('/admin/voting/result/' + this.id + '?tab=2')
                this.$message.success('Данные успешно сохранены')
              } else {
                this.$message.error(response.data.data)
              }
            })
        }).catch(() => {})
      } else {
        this.$message.error('Указаны не все ответы!')
      }
    },
    setStead(val) {
      this.stead = val
      this.editSteadNumber = false
      this.key++
    },
    setAnswer(val) {
      this.userAnswer = val
      this.validForm()
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
.inline {
  display: inline-block;
}
  .stead-number {
    position: relative;
    font-size: 1.5rem;
    background-color: #fff;
    font-weight: bold;
    display: inline-block;
    padding-right: 30px;
    padding-top: 15px;
    margin-bottom: 10px;
  }
.stead-number span {
  color: red;
  font-size: 2rem;
  background-color: #f3fc92;
  border-radius: 5px;
}
  .image {
    border: 1px solid #000;
    display: inline-block;
  }
  .stead-setting {
    position: absolute;
    right: 0;
    top: 0;
    color: #1b5fab;
  }
  .header {
    font-size: 1.25rem;
  }
  .voting-title {
    color: #001b44;
    margin-top: 10px;
    margin-bottom: 10px;
    font-size: 1.5rem;
  }
  .voting-block {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    padding-left: 40px;

  }
</style>
