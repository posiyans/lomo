<template>
    <el-card>
      <div class="text-h6 q-pa-md text-weight-bold">
        Сбросить пароль
      </div>
      <div class="q-pa-md">
        <span>Для восстановления пароля введите email использованный при регистрации</span>
      </div>
      <div class="article-preview-body" style="padding-left: 20px; width: 400px">
        <el-form
            ref="Resetform"
            :model="form"
            label-width="70px"
            :label-position="mobile ? 'top' : 'left'"
            :rules="loginRules"
        >
          <el-form-item label="E-mail" prop="email">
            <el-input
                ref="email"
                v-model="form.email"
                style="width: 300px;"
            />
          </el-form-item>
        </el-form>
      </div>
      <div class="q-ml-lg q-pa-lg">
        <q-btn color="primary" :loading="loading" no-caps label="Отправить ссылку для сброса пароля" @click="reset" />
      </div>
    </el-card>
</template>

<script>
import { defineComponent } from 'vue'
import { passwordReset } from 'src/Modules/User/api/auth'

export default defineComponent({
  setup () {
    return {
    }
  },
  data () {
    const validateEmail = (rule, value, callback) => {
      // console.log('valid eamil')
      const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
      if (!re.test(value)) {
        callback(new Error('Пожалуйста, введите действующий email'))
      } else {
        callback()
      }
    }
    return {
      form: {
        email: 'posiyans@yandex.ru'
      },
      loading: false,
      loginRules: {
        email: [{ required: true, trigger: 'blur', validator: validateEmail }]
      }
    }
  },
  computed: {
    mobile () {
      return this.$q.platform.is.mobile
    }
  },
  methods: {
    reset () {
      this.$refs.Resetform.validate(valid => {
        if (valid) {
          const data = {
            email: this.form.email
          }
          this.loading = true
          passwordReset(data)
            .then(response => {
              if (response.data) {
                this.$alert('На введенную вами почту было отправлено письмо со ссылкой для востановления пароля.', 'Внимание!!!', {
                  confirmButtonText: 'OK'
                })
              } else {
                this.$alert('Указанная вами почта не найдена, или сервис временно не доступен. Просьба проверить адрес почты или повторить позднее.', 'Ошибка', {
                  confirmButtonText: 'OK'
                })
              }
            }).finally(() => {
              this.loading = false
            })
        }
      })
    }
  }
})
</script>

<style scoped>

</style>
