<template>
  <div class="ps-card">
    <el-card>
      <div class="article-preview-header">
        <h2>Установить новый пароль</h2>
      </div>
      <div class="article-preview-body">
        <span>Введите новвый пароль</span>
      </div>
      <div class="article-preview-body" style="padding-left: 20px; width: 400px">
        <el-form
          ref="Resetform"
          :model="form"
          label-width="120px"
          label-position="top"
          :rules="loginRules"
        >
          <el-form-item label="E-mail" prop="email">
            <el-input
              ref="email"
              v-model="form.email"
              name="email"
              readonly
            />
          </el-form-item>
          <el-form-item label="Пароль" prop="password">
            <el-input
              v-model="form.password"
              show-password
              tabindex="1"
            />
          </el-form-item>
          <el-form-item label="Потвердить пароль" prop="password_confirmation">
            <el-input
              v-model="form.password_confirmation"
              show-password
              tabindex="2"
            />
          </el-form-item>
        </el-form>
      </div>
      <div class="article-preview-footer">
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="12"><div style="padding-left: 20px;"><el-button type="primary" size="mini" plain @click="save">Сохранить</el-button></div></el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
import { passwordChange } from '@/api/user/user.js'

export default {
  data() {
    const validateEmail = (rule, value, callback) => {
      // console.log('valid eamil')
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
      form: {
        email: null,
        token: '',
        password: '',
        password_confirmation: ''
      },
      loginRules: {
        email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        password: [{ required: true, trigger: 'blur', validator: validatePassword }],
        password_confirmation: [{ required: true, trigger: 'blur', validator: validatePasswordReplay }]
      }
    }
  },
  mounted() {
    this.form.email = this.$route.query.email
    this.form.token = this.$route.query.token
  },
  methods: {
    save() {
      this.$refs.Resetform.validate(valid => {
        if (valid) {
          passwordChange(this.form)
            .then(response => {
              if (response.data) {
                window.location = '/'
                // const { allPermissions } = await store.dispatch('user/getInfo')
                // // console.log(roles)
                // // generate accessible routes map based on roles
                // const accessRoutes = await store.dispatch('permission/generateRoutes', allPermissions)
                // // dynamically add accessible routes
                // router.addRoutes(accessRoutes)
                // await store.dispatch('permission/getMenu')
              } else {
                this.$alert('Упс. Что-то пошло не так, попробуйье еще раз.', 'Ошибка', {
                  confirmButtonText: 'OK'
                })
              }
              // console.log(response.data)
            })
          // console.log('ok')
          // password/email
        }
      })
    }
  }
}
</script>

<style scoped>
  .ps-card{
    padding: 0 5px 10px 5px;
  }
  .article-preview-header{
    padding: 0 20px;
    border-bottom: 1px solid #e6ebf5;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #303133;
    margin-top: -17px;
  }
  .article-preview-body{
    padding: 20px 0;
  }
  .article-preview-footer {

  }
  .article-preview-body >>> img{
    width: 100px;
    float:left; /* Выравнивание по левому краю */
    margin: 0 20px 0 0; /* Отступы вокруг картинки */
  }
</style>
