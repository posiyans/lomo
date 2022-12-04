<template>
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
      <el-form-item label="" prop="remember">
        <el-checkbox v-model="form.remember">Запомнить меня</el-checkbox>
      </el-form-item>
      <el-form-item>
        <el-button class="w-100" type="primary" @click="login">Войти</el-button>
      </el-form-item>
    </el-form>
    <div class="row items-center">
      <div class="q-mr-md">
        или через
      </div>
      <LoginByVkBtn />
    </div>
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
        email: 'posiyans@yandex.ru',
        password: '1234567',
        remember: true
      },
      // key: 1,
      showLoginForm: false,
      showRegisterForm: false
    }
  },
  computed: {
    label_position () {
      return this.$q.platform.is.desktop ? 'left' : 'top'
    }
  },
  methods: {
    resetPassword () {
      this.$emit('close')
      this.$router.push('/auth/password-reset')
    },
    login () {
      this.$refs.loginForm.validate((valid) => {
        if (valid) {
          const userStore = useUserStore()
          userStore.login(this.form)
        }
      })
    }
  }
})
</script>

<style scoped>
  .reset-password{
    display: inline-block;
    float: right;
    margin-top: -27px;
    color: #0482ff;
    cursor: pointer;
  }
</style>
