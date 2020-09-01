<template>
  <div class="header">
  <el-button type="text" icon="el-icon-star-on" @click="showLoginForm = !showLoginForm">Войти</el-button>
  <el-dialog
  title="Войти на сайт"
  :fullscreen="device == 'mobile' ? true : false"
  :visible.sync="showLoginForm"
  width="600px"
  center>
    <div slot="title" style="background-color: rgb(84, 92, 100); color: #ffffff; font-weight: bold; font-size: 16px">
      Войти на сайт
    </div>
    <div class="login-form">
      <el-form
        ref="loginForm"
        :model="form"
        label-width="80px"
        :label-position="label_position"
        :rules="rules"
        size="mini"
      >
        <el-form-item label="E-mail" prop="email">
          <div class="reset-password" @click="resetPassword" >Забыли пароль?</div>
          <el-input v-model="form.email"></el-input>
        </el-form-item>
        <el-form-item label="Пароль" prop="password">
          <el-input v-model="form.password" show-password></el-input>
        </el-form-item>
        <el-form-item>
          <el-form-item label="" prop="type">
            <el-checkbox v-model="form.remember">Запомнить меня</el-checkbox>
          </el-form-item>
          <el-button type="primary" @click="login">Войти</el-button>

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
      return {
        rules: {
          email: [
            { required: true, message: 'Введите свой E-mail', trigger: 'blur' },
            { type: 'email', message: 'Должен быть валидный email', trigger: 'blur' }
          ],
          password: [
            { required: true, message: 'Введите пароль', trigger: 'blur' },
          ],
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
      register() {
        console.log('close!!!!!!!!!!!')
      },
      resetPassword(){
        this.showLoginForm = false
        this.$router.push('/password/reset')
      },
      login() {
        this.$refs['loginForm'].validate((valid) => {
          if (valid) {
            this.$store.dispatch('user/login', this.form)
              .then(() => {
                this.$router.push({path: this.redirect || '/', query: this.otherQuery})
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
    margin-top: -30px;
    color: #0482ff;
    cursor: pointer;
  }
  .login-form >>> .el-form-item--medium .el-form-item__label {
    line-height: 0px;
    padding: 0;
  }
  .login-form >>> .el-form-item__label {
    padding: 0;
  }

  /*.login-form >>> .el-form-item {*/
  /*  margin-bottom: 6px;*/

  /*}*/
  /*.login-form {*/
  /*  position: static;*/
  /*}*/

  .login-form a {
    /*position: relative;*/
    /*top: 20px;*/
    float: right;
    font-size: 12px;
    margin-top: -28px;
    margin-right: 10px;

  }
  .login-form span{
    /*position: relative;*/
    /*top: 20px;*/
    float: right;
    font-size: 14px;
    margin-top: 0px;
    margin-right: 10px;

  }
  .login-form >>> .social-signup-container {
    line-height: 20px;
  }
  /*.header {*/
  /*  line-height: 60px;*/
  /*}*/
  /* .layout-header {*/
  /*   background-color: #00084c;*/
  /*   color: #fff;*/
  /* }*/

</style>


