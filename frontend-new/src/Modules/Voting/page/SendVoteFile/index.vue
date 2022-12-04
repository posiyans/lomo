<template>
  <div v-loading="loading">
    <div v-if="!noFound">
      <div>
        <div class="q-pa-md text-weight-bold text-h6 text-primary cursor-pointer" @click="pushVoting">{{ voting.title }}</div>
        <div class="q-pa-md cursor-pointer">Добавить бюллетень для голосования</div>
        <div class="text-secondary q-pa-md bg-white">
          Для участия в голосовании необходимо скачать бюллетень
          <span class="text-red cursor-pointer" @click="pushVoting">
            на станице голосования</span>.<br>
          Заполнить его и прикрепить ниже фотографию или скан заполненого бюллетня.<br>
          В голосвании могут участвовать только собственники участков в СНТ.
        </div>
        <div class="q-pa-md text-big-20">
          Укажите участок
        </div>
        <SelectStead v-model="stead" outlined dense label="Выбирете участок" />
        {{stead}}
      </div>
      <div v-if="stead">
        <div class="q-pa-md">
          Укажите номер телефона собственника, на него будет <strong>выслан</strong> код подтверждения.
        </div>
        <q-input
            filled
            v-model="phone"
            label="Телефон"
            mask="+7(###) ###-##-##"
            hint="+7(911) 111-11-11"
            :readonly="code"
        />
        <div class="q-pa-md">
          <q-btn v-if="!code"  no-wrap no-caps color="primary" label="Отправить код" :disabled="!valid" @click="sendCode" />
        </div>
        <div v-if="valid & !code">
          <div class="q-pa-sm">
            Код подтвеждения
          </div>
          <q-input
              filled
              v-model="codeNumber"
              label="Код из смс"
              mask="#-#-#-#"
              hint="+7(911) 111-11-11"
              @update:model-value="codeChange"
          />
          {{codeNumber}}
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
          <el-button type="primary">Выбрать файл</el-button>
          <div class="el-upload__tip">Фото или скан заполненного бюллетеня размером не более 5Mb</div>
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
import SelectStead from 'src/Modules/Stead/components/SelectStead/index.vue'
import { fetchUserVoting, votingSendSms, votingValidSms } from 'src/Modules/Voting/api/voting.js'

export default {
  components: {
    SelectStead
  },
  data() {
    return {
      phone: '+7(911) 961-27-47',
      stead: 372,
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
      const pattern = /^\+?7?\s*?\(?\d{3}(?:\)|[-|\s])?\s*?\d{3}[-|\s]?\d{2}[-|\s]?\d{2}$/
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
      console.log(this.codeNumber)
      console.log(this.codeNumber.length)
      if (this.codeNumber.length === 7) {
        const data = {
          phone: this.phone,
          code: this.codeNumber
        }
        votingValidSms(data)
          .then(response => {
            console.log(response.data.status)
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
                this.$message.success('СМС успешно отправлено')
              } else {
                this.$message.success('Номер уже подтверждён')
                this.code = true
              }
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      }).catch(() => {
        this.$message.error('Ошибка отправки СМС')
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
