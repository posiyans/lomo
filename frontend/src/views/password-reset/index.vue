<template>
  <div class="ps-card">
    <el-card>
      <div class="article-preview-header">
        <h2>Сбросить пароль</h2>
      </div>
      <div class="article-preview-body" >
        <span>Для восстановления пароля введите email использованный при регистрации</span>
      </div>
      <div class="article-preview-body" style="padding-left: 20px; width: 400px">
          <el-form
            ref="Resetform"
            :model="form"
            label-width="70px"
            label-position="left"
            :rules="loginRules"
          >
            <el-form-item label="E-mail" prop="email">
              <el-input
                v-model="form.email"
                tabindex="1"
                ref="email"
              ></el-input>
            </el-form-item>

          </el-form>
      </div>
      <div class="article-preview-footer">
        <el-row type="flex" class="row-bg" justify="space-between" align="center">
          <el-col :span="12"><div style="padding-left: 20px;"><el-button type="primary" size="mini" plain @click.prevent="reset">Отправить ссылку для сброса пароля</el-button></div></el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
  import { passwordReset } from '@/api/user/user.js'
  export default {
    data() {
      const validateEmail = (rule, value, callback) => {
        console.log('valid eamil')
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
        if (!re.test(value )) {
          callback(new Error('Пожалуйста, введите действующий email'))
        } else {
          callback()
        }
      }
      return {
        form: {
          email: null
        },
        loginRules: {
          email: [{ required: true, trigger: 'blur', validator: validateEmail }],
        },

      }
    },
    methods:{
      reset(){
        this.$refs.Resetform.validate(valid => {
          console.log(valid)
          if (valid) {
            const data = {
              email: this.form.email
            }
            passwordReset(data)
              .then(response =>{
                if (response.data){
                  this.$alert('На введенную вами почту было отправлено письмо со ссылкой для востановления пароля.', 'Внимание!!!', {
                    confirmButtonText: 'OK',
                  });
                } else {
                  this.$alert('Указанная вами почта не найдена, или сервис временно не доступен. Просьба проверить адрес почты или повторить позднее.', 'Ошибка', {
                    confirmButtonText: 'OK',
                  });
                }
                console.log(response.data)
              })
            console.log('ok')
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
    margin: 0px 20px 0 0; /* Отступы вокруг картинки */
  }
</style>
