<template>
  <div class="ml2 mr2 mr4-ns">
    <el-button type="text" @click="showLoginForm = !showLoginForm">Войти</el-button>
    <el-dialog
      title="Войти на сайт"
      :fullscreen="device == 'mobile' ? true : false"
      :visible.sync="showLoginForm"
      width="600px"
      center
    >
      <div slot="title" class="main-header__modal_title">
        Войти на сайт
      </div>
      <div class="main-header_login-form">
        <el-form
          ref="loginForm"
          :model="form"
          label-width="80px"
          :label-position="label_position"
          :rules="rules"
          size="mini"
        >
          <el-form-item label="E-mail" prop="email">
            <div class="reset-password" @click="resetPassword">Забыли пароль?</div>
            <el-input v-model="form.email" />
          </el-form-item>
          <el-form-item label="Пароль" prop="password">
            <el-input v-model="form.password" show-password />
          </el-form-item>
          <el-form-item>
            <el-form-item label="" prop="type">
              <el-checkbox v-model="form.remember">Запомнить меня</el-checkbox>
            </el-form-item>
            <el-button size="default" class="w-100" type="primary" @click="login">Войти</el-button>
          </el-form-item>
        </el-form>
        <div class="flex">
          <div>
            или через
          </div>
          <div class="ml2">
            <div @click="loginVK">
              <img src="/images/vk-banner.png" width="100px">
            </div>
          </div>
        </div>
      </div>
      <div v-if="device === 'mobile'" slot="footer" class="main-header__modal_title">
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
    return {
      rules: {
        email: [
          { required: true, message: 'Введите свой E-mail', trigger: 'blur' },
          { type: 'email', message: 'Должен быть валидный email', trigger: 'blur' }
        ],
        password: [
          { required: true, message: 'Введите пароль', trigger: 'blur' }
        ]
      },
      form: {
        email: '',
        password: '',
        remember: true
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
      return this.device === 'desktop' ? 'left' : 'top'
    }
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
      // console.log('vk')

      const $url = await this.$store.dispatch('user/loginVK')
      window.location = $url
    },
    register() {
      console.log('close!!!!!!!!!!!')
    },
    resetPassword() {
      this.showLoginForm = false
      this.$router.push('/password/reset')
    },
    login() {
      this.$refs['loginForm'].validate((valid) => {
        if (valid) {
          this.$store.dispatch('user/login', this.form)
            .then(() => {
              this.$router.push({ path: this.redirect || '/', query: this.otherQuery })
              this.showLoginForm = false
            })
            .catch(() => {
              this.loading = false
            })
        }
      })
    }
  }
}
</script>

<style scoped>
  .reset-password{
    display: inline-block;
    float:right;
    margin-top: -27px;
    color: #0482ff;
    cursor: pointer;
  }
</style>
