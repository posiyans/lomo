<template>
  <div class="ml2 mr2 mr4-ns">
    <el-button @click="showLoginForm = !showLoginForm">
      Регистрация
    </el-button>
    <el-dialog
      title="Регистрация на сайте"
      :fullscreen="mobile ? true : false"
      v-model="showLoginForm"
      width="600px"
      center
    >
      <div class="main-header_login-form">
        <el-form
          ref="Registerform"
          :model="form"
          label-width="170px"
          label-position="top"
          size="mini"
          :rules="loginRules"
        >
          <el-form-item label="Имя" prop="name">
            <el-input
              ref="name"
              v-model="form.name"
              tabindex="1"
            />
          </el-form-item>
          <el-form-item label="E-mail" prop="email">
            <el-input
              ref="email"
              v-model="form.email"
              tabindex="2"
              name="email"
            />
          </el-form-item>
          <el-form-item label="Пароль" prop="password">
            <el-input
              v-model="form.password"
              show-password
              tabindex="3"
            />
          </el-form-item>
          <el-form-item label="Подтвердить пароль" prop="password_confirmation">
            <el-input
              v-model="form.password_confirmation"
              show-password
              tabindex="4"
            />
          </el-form-item>
          <el-form-item label="">
            <div class="row no-wrap">
              <div>
                <el-checkbox v-model="personal" />
              </div>
              <div class="do-not-carry personal" :class="{ blue: personal }">
                Я подтверждаю своё согласие на обработку и передачу информации в электронной Форме (обращения) (в том числе персональных данных) по открытым каналам связи сети Интернет.
              </div>
            </div>
          </el-form-item>
          <el-form-item>
            <el-button :loading="loading"  class="w-100" type="primary" :disabled="!personal" @click="handleRegister">Зарегистрироваться</el-button>
          </el-form-item>
        </el-form>
        <div class="row">
          <div class="q-mr-sm">
            или через
          </div>
          <LoginByVkBtn />
        </div>
      </div>
      <div class="text-right">
        <el-button type="primary" @click="showLoginForm = false">Закрыть</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import LoginByVkBtn from 'src/Modules/User/components/LoginByVkBtn/index.vue'
import { defineComponent } from 'vue'
import { useUserStore } from 'src/Modules/User/store/user-store'

export default defineComponent({
  components: {
    LoginByVkBtn
  },
  data () {
    const validateName = (rule, value, callback) => {
      if (value.length === 0) {
        callback(new Error('Имя не может быть пустым'))
      } else {
        callback()
      }
    }
    const validateEmail = (rule, value, callback) => {
      const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
      if (!re.test(value)) {
        callback(new Error('Пожалуйста, введите действующий email'))
      } else {
        callback()
      }
    }
    const validatePassword = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('Пароль не может быть менее 8 символов'))
      } else {
        callback()
      }
    }
    const validatePasswordReplay = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('Пожалуйста, введите пароль еще раз'))
      } else if (value !== this.form.password) {
        callback(new Error('Пароли не совпадают!'))
      } else {
        callback()
      }
    }
    return {
      personal: false,
      loading: false,
      form: {
        name: '',
        email: 'posiyans2@yandex.ru',
        password: 'Q12345678',
        password_confirmation: 'Q12345678'
      },
      loginRules: {
        email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        name: [{ required: true, trigger: 'blur', validator: validateName }],
        password: [{ required: true, trigger: 'blur', validator: validatePassword }],
        password_confirmation: [{ required: true, trigger: 'blur', validator: validatePasswordReplay }]
      },
      // key: 1,
      showLoginForm: false,
      showRegisterForm: false
    }
  },
  computed: {
    mobile () {
      return this.$q.platform.is.mobile
    },
    key () {
      return this.$route.path
    }
  },
  mounted () {
    // console.log('this.$refs')
    // console.log(this.$refs)
    // if (this.form.email === '') {
    //   this.$refs.email.focus()
    // }
  },
  methods: {
    async handleCommand (command) {
      if (command === 'logout') {
        console.log('logout')
        await this.$store.dispatch('user/logout')
        await this.$store.dispatch('user/getInfo')
        // this.key += 1
      }
    },
    handleRegister () {
      this.$refs.Registerform.validate(valid => {
        if (valid) {
          this.loading = true
          const userStore = useUserStore()
          userStore.register(this.form)
            .finally(() => {
              this.loading = false
            })
        } else {
          return false
        }
      })
    },
    login () {
      this.$store.dispatch('user/login', this.form)
        .then(() => {
          this.$router.push({ path: this.redirect || '/', query: this.otherQuery })
          this.showLoginForm = false
        })
        .catch(() => {
          this.loading = false
        })
    }
  }
})
</script>

<style scope>
  .personal {
    margin-left: 10px;
    font-size: 0.9em;
    line-height: 1.3em;
  }
</style>
