<template>
  <RightCard header="Войти на сайт">
    <template>
      <div class="login-form">
        <el-form
          ref="form"
          :model="form"
          label-width="120px"
          label-position="top"
          size="mini"
        >
          <el-form-item label="E-mail">
            <div class="reset-password" @click="resetPassword" >Забыли пароль?</div>
            <el-input v-model="form.email"></el-input>
          </el-form-item>
          <el-form-item label="Пароль">
            <el-input v-model="form.password" show-password></el-input>
          </el-form-item>
          <el-form-item>
          <el-form-item label="" prop="type">
              <el-checkbox v-model="form.remember">Запомнить меня</el-checkbox>
          </el-form-item>
            <el-button type="primary" @click="login">Войти</el-button>
            <span>или через</span>
          </el-form-item>
        </el-form>
        <div @click="loginVK"><img src="/images/vk-banner.png" width="100px"></div>

      </div>
    </template>
  </RightCard>
</template>

<script>
import RightCard from '@/components/RightCard'
import store from '../../store'
export default {
  name: 'LoginForm',
  components: {
    RightCard
  },
  props: {
    showLoginForm: {
      type: Boolean
    }
  },
  data() {
    return {
      intervalid: '',
      newWin: null,
      form: {
        email: '',
        password: '',
        remember: true
      }
    }
  },
  mounted() {
    console.log(this.showLoginForm)
  },
  computed: {

  },
  methods: {
    resetPassword(){
      this.$router.push('/password/reset')
    },
    async loginVK() {
      console.log('vk')

      const $url = await this.$store.dispatch('user/loginVK')
      console.log($url)
      console.log(window.location.pathname)
      console.log(this.$route.query.page)
      console.log(location.href)
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
    register(){
      // console.log(this.showLoginForm)

      //this.$emit(true)
      this.$router.push('/')

      console.log('register')
    },
    test() {
      console.log('close!!!!!!!!!!!')
    },
    login() {
      this.$store.dispatch('user/login', this.form)
        .then(() => {
          this.$router.push({ path: this.redirect || '/', query: this.otherQuery })
          this.loading = false
        })
        .catch(() => {
          this.loading = false
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

  .login-form >>> .el-form-item {
    margin-bottom: 6px;

  }
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


