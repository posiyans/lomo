<template>
  <div class="header">
  <el-button type="text" icon="el-icon-star-on" @click="showLoginForm = !showLoginForm">Регистрация</el-button>
  <el-dialog
  title=""
  :fullscreen="device == 'mobile' ? true : false"
  :visible.sync="showLoginForm"
  width="600px"
  center>
    <div slot="title" style="background-color: rgb(84, 92, 100); color: #ffffff; font-weight: bold; font-size: 16px">
      Регистрация на сайте
    </div>
    <div class="login-form">
      <el-form
        ref="Registerform"
        :model="form"
        label-width="160px"
        :label-position="label_position"
        size="mini"
        :rules="loginRules"
      >
        <el-form-item label="Имя" prop="name">
          <el-input
            v-model="form.name"
            tabindex="1"
          ></el-input>
        </el-form-item>
        <el-form-item label="E-mail" prop="email">
          <el-input
            v-model="form.email"
            tabindex="2"
            name="email"
            ref="email"
          ></el-input>
        </el-form-item>
        <el-form-item label="Пароль" prop="password">
          <el-input
            v-model="form.password"
            show-password
            tabindex="3"
          ></el-input>
        </el-form-item>
        <el-form-item label="Потвердить пароль" prop="password_confirmation">
          <el-input
            v-model="form.password_confirmation"
            show-password
            tabindex="4"
          ></el-input>
        </el-form-item>
        <el-form-item>
          <el-button :loading="loading" type="primary" style="width:100%;margin-bottom:30px;" @click.native.prevent="handleRegister">Зарегистрироваться</el-button>
        </el-form-item>
      </el-form>
      <div style="line-height: 30px;"><div style="display: inline-block; width: 80px;">или через</div><div style="display: inline-block;"><div @click="loginVK"><img src="/images/vk-banner.png" width="100px"></div></div></div>

    </div>
    <div v-if="device === 'mobile'" slot="footer" style="background-color: rgb(84, 92, 100); color: #ffffff; font-weight: bold; font-size: 16px">
      <el-button type="primary" @click="showLoginForm = false">Закрыть</el-button>
    </div>
  </el-dialog>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  export default {
    name: 'LoginModal',
    components: {
    },
    data() {
      const validateName = (rule, value, callback) => {
        if (value.length === '') {
          callback(new Error('Имя не может быть пустым'))
        } else {
          callback()
        }
      }
      const validateEmail = (rule, value, callback) => {
        console.log('valid eamil')
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
        if (!re.test(value )) {
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
          callback(new Error('Пожалуйста, введите пароль еще раз'));
        } else if (value !== this.form.password) {
          callback(new Error('Пароли не совпадают!'));
        } else {
          callback();
        }
      }
      return {
        loading: false,
        form: {
          name: '',
          email: '',
          password: '',
          password_confirmation: ''
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
      ...mapGetters([
        'sidebar',
        'avatar',
        'device',
        'user'
      ]),
      roleGuest() {
        return this.$store.getters.roles.includes('guest')
      },
      cachedViews() {
        // console.log('this.$store.state.tagsView.cachedViews')
        // console.log(this.$store.state.tagsView.cachedViews)
        return this.$store.state.tagsView.cachedViews
      },
      key() {
        return this.$route.path
      },
      label_position() {
        return this.device === 'desktop' ? 'right' : 'top'
  }
    },
    mounted() {
      // console.log('this.$refs')
      // console.log(this.$refs)
      // if (this.form.email === '') {
      //   this.$refs.email.focus()
      // }
    },
    methods: {
      async handleCommand(command) {
        if (command === 'logout') {
          console.log('logout')
          await this.$store.dispatch('user/logout')
          await this.$store.dispatch('user/getInfo')
          // this.key += 1
        }
      },
      async loginVK() {
        console.log('vk')

        const $url = await this.$store.dispatch('user/loginVK')
        // console.log($url)
        // console.log(window.location.pathname)
        // console.log(this.$route.query.page)
        // console.log(location.href)
        window.location = $url
        // window.location = $url + '&redirect_uri=' + location.href
        // this.newWin = window.open($url, "hello", "width=200,height=200");
        // this.intervalid = setInterval(() => {
        //   console.log('interval')
        //   console.log(this.newWin)
        //   if (this.newWin === null || this.newWin.closed) {
        //     clearInterval(this.intervalid)
        //     store.dispatch('user/getInfo')
        //   }
        // }, 1000);
        // newWin.addEventListener('beforeunload', this.test)
        // newWin.addEventListener('resultCloseParent', this.test)
      },
      handleRegister() {
        console.log('register!!!!!!!!!!!')
        console.log(this.$refs)
        this.$refs.Registerform.validate(valid => {
          console.log(valid)
          if (valid) {
            this.loading = true
            console.log('user/register')
            this.$store.dispatch('user/register', this.form)
              .then(() => {
                this.$router.push({ path: this.redirect || '/', query: this.otherQuery })
                this.loading = false
              })
              .catch(() => {
                this.loading = false
              })
          } else {
            console.log('error submit!!')
            return false
          }
        })
      },
      login() {
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
  }
</script>
