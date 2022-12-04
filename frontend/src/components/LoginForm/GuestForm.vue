<template>
  <RightCard header="Войти на сайт">
    <template>
      <div class="login-form">
        <el-form
          ref="loginForm"
          :model="form"
          label-width="120px"
          label-position="top"
          :rules="rules"
          size="mini"
          @submit="login"
        >
          <el-form-item label="E-mail" prop="email">
            <div class="reset-password" @click="resetPassword">Забыли пароль?</div>
            <el-input v-model="form.email" />
          </el-form-item>
          <el-form-item label="Пароль" prop="password">
            <el-input v-model="form.password" show-password />
          </el-form-item>
          <el-form-item label="" prop="type">
            <el-checkbox v-model="form.remember">Запомнить меня</el-checkbox>
          </el-form-item>
          <div>
            <el-button type="primary" @click="login">Войти</el-button>
          </div>
        </el-form>
        <div class="q-mt-sm row items-center no-wrap justify-between">
          <div>
            или через
          </div>
          <LoginByVkBtn class="q-pt-sm" />
        </div>
      </div>
    </template>
  </RightCard>
</template>

<script>
import RightCard from '@/components/RightCard'
import LoginByVkBtn from '@/Modules/User/components/LoginByVkBtn'

export default {
  name: 'LoginForm',
  components: {
    RightCard,
    LoginByVkBtn
  },
  props: {
    showLoginForm: {
      type: Boolean
    }
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
      intervalid: '',
      newWin: null,
      form: {
        email: '',
        password: '',
        remember: true
      }
    }
  },
  methods: {
    resetPassword() {
      this.$router.push('/password/reset')
    },
    register() {
      // console.log(this.showLoginForm)

      // this.$emit(true)
      this.$router.push('/')

      console.log('register')
    },
    test() {
      console.log('close!!!!!!!!!!!')
    },
    login() {
      this.$refs['loginForm'].validate((valid) => {
        if (valid) {
          this.$store.dispatch('user/login', this.form)
            .then(() => {
              this.$router.push({ path: this.redirect || '/', query: this.otherQuery })
              this.loading = false
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

