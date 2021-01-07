<template>
  <div v-loading="loading">
    <div v-if="!noFound">
      <div>
        <div class="pa1 f2 dark-blue fw6 pointer" @click="pushVoting">{{ voting.title }}</div>
        <div class="pa1 f3 pointer" @click="pushVoting">Добавить бюллетень для голосования</div>
        <div class="dark-green ba bg-white pa2 dib ma1 br1">
          Для участия в голосовании необходимо скачать бюллетень
          <span class="dark-red pointer" @click="pushVoting">
            на станице голосования</span>.<br>
          Заполнить его и прикрепить ниже фотографию или скан заполненого бюллетня.<br>
          В голосвании могут участвовать только собственники участков в СНТ.
        </div>
        <div class="pl2 pa1 f5">
          Укажите участок
        </div>
        <UserSteadFind @selectStead="setStead" />
      </div>
      <div v-if="stead">
        <div class="pl2 pa1 f5">
          Укажите номер телефона собственника, на него будет <strong>выслан</strong> код подтверждения.
        </div>
        <el-input
          v-model="phone"
          v-mask="'+7(###) ###-##-##'"
          type="text"
          placeholder="+7(911) 111-11-11"
          :readonly="code"
          style="width: 206px;"
        />
        <div class="pt2">
          <el-button v-if="!code" type="primary" :disabled="!valid" @click="sendCode">Отправить код</el-button>
        </div>
        <div v-if="valid & !code">
          <div class="pl2 pa1">
            Код подтвеждения
          </div>
          <el-input
            v-model="codeNumber"
            v-mask="'#-#-#-#'"
            style="width: 206px;"
            placeholder="Введите код из смс"
            @keyup.native="codeChange"
          />
        </div>
      </div>
      <div v-if="code" class="pt3 pb3">
        <el-upload
          ref="upload"
          :action="url"
          with-credentials
          :headers="token"
          :data="sendData"
          :on-success="uploadSuccess"
          :on-change="validFile"
          :auto-upload="false"
          class="upload-demo"
        >
          <el-button slot="trigger" type="primary">Выбрать фаил</el-button>
          <div slot="tip" class="el-upload__tip">Фото или скан заполненного бюллетеня размером не более 5Mb</div>
        </el-upload>
      </div>
      <div v-if="code" сlass="pt3 pb6">
        <div class="pt3 pb3">
          <el-checkbox v-model="checked">Я подтверджаю, что предоставленные персональные данные являются достоверными и я даю своё согласие на их использование, обработку и хранение.</el-checkbox>
        </div>
        <el-button type="success" :disabled="!checked" @click="submitUpload">Отправить файл</el-button>
      </div>
    </div>
    <div v-if="noFound" class="no-found">
      Голосование не найдено или оно уже прошло
    </div>
  </div>
</template>

<script>
import UserSteadFind from '@/components/UserSteadFind'
import { fetchUserVoting, votingSendSms, votingValidSms } from '@/api/user/voting'

export default {
  components: {
    UserSteadFind
  },
  data() {
    return {
      phone: '',
      stead: '',
      code: false,
      codeNumber: '',
      checked: false,
      token: { 'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN') },
      noFound: false,
      voting: '',
      loading: true
    }
  },
  computed: {
    valid() {
      var pattern = new RegExp(/^\+?7?\s*?\(?\d{3}(?:\)|[-|\s])?\s*?\d{3}[-|\s]?\d{2}[-|\s]?\d{2}$/)
      return pattern.test(this.phone)
    },
    url() {
      return process.env.VUE_APP_BASE_API + '/api/v1/user/voting/file/upload'
    },
    sendData() {
      return { voting: this.$route.params.id, phone: this.phone, stead_id: this.stead.id, stead_number: this.stead.number }
    }
  },
  mounted() {
    this.fetchVoting()
  },
  methods: {
    validFile(file) {
      if (file.size > 5000000) {
        this.$message.error('Файл превышает допустимый размер')
        this.$refs.upload.clearFiles()
      }
    },
    pushVoting() {
      this.$router.push('/voting/show/' + this.$route.params.id)
    },
    fetchVoting() {
      this.noFound = false
      fetchUserVoting(this.$route.params.id)
        .then(response => {
          if (response.data.status) {
            this.voting = response.data.data
            if (this.voting.type === 'public' || this.voting.status === 'done') {
              this.noFound = true
            }
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
            this.noFound = true
          }
          this.loading = false
        })
    },
    uploadSuccess(response) {
      if (response.status) {
        this.$alert('Файл успешно отправлен!', 'Успех', {
          confirmButtonText: 'OK',
          callback: action => {
            this.stead = false
            this.phone = ''
            this.code = false
            this.codeNumber = ''
            this.checked = false
            this.pushVoting()
          }
        })
      } else {
        this.$alert('Ошибка отправки файла, попробуйте позже!', 'Ошибка', {
          confirmButtonText: 'OK',
          type: 'error'
        })
      }
    },
    getCookie(name) {
      const matches = document.cookie.match(new RegExp(
        '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'
      ))
      return matches ? decodeURIComponent(matches[1]) : undefined
    },
    codeChange() {
      if (this.codeNumber.length === 7) {
        //
        const data = {
          phone: this.phone,
          code: this.codeNumber
        }
        votingValidSms(data)
          .then(response => {
            if (response.data.status) {
              this.code = true
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      }
    },
    sendCode() {
      this.$confirm('Отправить код на номер ' + this.phone + ' ?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        const data = {
          phone: this.phone
        }
        votingSendSms(data)
          .then(response => {
            if (response.data.status) {
              if (response.data.send) {
                this.$message.success('Смс успешно отправлено')
              } else {
                this.$message.success('Номер уже потдвержден')
                this.code = true
              }
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      }).catch(() => {
      })
    },
    setStead(val) {
      this.stead = val
    },
    submitUpload() {
      this.$refs.upload.submit()
    }
  }
}
</script>

<style scoped>

</style>
