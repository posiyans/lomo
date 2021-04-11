6<template>
  <div>
    <el-card>
      <div class="article-preview-header">
        <h2>Профиль</h2>
      </div>
      <div class="article-preview-body" style="padding: 0 10px; max-width: 600px; margin: 0 auto">
        <AvatarUpload v-model="user.avatar" :user-id="user.id" />
      </div>
      <div class="article-preview-body" style="padding: 0 10px; max-width: 600px; margin: 0 auto">
        <el-form
          ref="form"
          :model="form"
          label-width="220px"
          label-position="top"
        >
          <el-form-item label="E-mail">
            <el-input v-model="user.email" type="email" :readonly="email_write" placeholder="Укажите свой электронный ящик" />
          </el-form-item>
          <el-form-item label="Фамилия">
            <el-input v-model="user.last_name" />
          </el-form-item>
          <el-form-item label="Имя">
            <el-input v-model="user.name" />
          </el-form-item>
          <el-form-item label="Отчество">
            <el-input v-model="user.middle_name" />
          </el-form-item>
          <el-form-item label="Телефон">
            <el-input v-model="user.phone" v-mask="'+7(###)###-##-##'" />
          </el-form-item>
          <el-form-item label="Адрес регистрации/почтовый адрес">
            <el-input v-model="user.adres" type="textarea" />
          </el-form-item>
          <div class="message">Все поля обязтельны для заполнения, и необходимы для вашей идентификации администрацией сайта </div>
          <el-form-item label="Собственник">
            <el-checkbox v-model="owner" name="type" />
          </el-form-item>
          <el-form-item v-if="user.steads_id" label="Номер Участка(ов)">
            <el-select
              v-model="user.steads_id"
              multiple
              filterable
              remote
              reserve-keyword
              placeholder="Введите номер участка"
              no-data-text="Данный номер не найден"
              :remote-method="findStead"
              :loading="loading"
            >
              <el-option
                v-for="item in steadsList"
                :key="item.id"
                :label="item.number"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="Cогласен">
            <div class="flex">
              <div>
                <el-checkbox v-model="user.consent_personal" />
              </div>
              <div class="do-not-carry personal" :class="{ blue: user.consent_personal }">
                Я подтверждаю своё согласие на обработку и передачу информации в электронной Форме (обращения) (в том числе персональных данных) по открытым каналам связи сети Интернет.
              </div>
            </div>
          </el-form-item>
          <el-form-item label="Прикрепить соц.сеть">
            <div v-if="user.socialList.includes('vkontakte')" class="socalConfirmed"><img src="/images/vk-banner.png" width="100px"></div>
            <div v-else @click="loginVK"><img src="/images/vk-banner.png" width="100px"></div>

          </el-form-item>
          <el-form-item>
            <el-button type="primary" :disabled="!user.consent_personal" @click="saveData">Сохранить</el-button>
            <el-button @click="resetProfile">Отменить</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
  </div>
</template>

<script>
import { getInfo, setUserInfo } from '@/api/user/user.js'
import { getSteadsList } from '@/api/user/stead.js'
import AvatarUpload from '@/components/AvatarUpload'
import { mapState } from 'vuex'

export default {
  filters: {
    noEmailFilter(val) {
      // if (val.substr(0, 8))
      return 'hjhjghjhgj'
    }
  },
  components: {
    AvatarUpload
  },
  data() {
    return {
      data: false,
      user: {
        steads: false
      },
      form: {
        name: '',
        region: '',
        date1: '',
        date2: '',
        delivery: false,
        type: [],
        resource: '',
        desc: ''
      },
      email_write: true,
      loading: false,
      steadsList: []
    }
  },
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      device: state => state.app.device,
      needTagsView: state => state.settings.tagsView,
      fixedHeader: state => state.settings.fixedHeader
    }),
    labelPosition() {
      return this.device === 'desktop' ? 'left' : 'top'
    },
    owner: {
      get() {
        if (this.user.steads_id !== null) {
          return true
        }
        return false
      },
      set(val) {
        if (val) {
          this.user.steads_id = []
        } else {
          this.user.steads_id = null
        }
      }
    },
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile'
      }
    }
  },
  mounted() {
    this.getInfo()
  },
  methods: {
    async loginVK() {
      const $url = await this.$store.dispatch('user/loginVK')
      window.location = $url
    },
    resetProfile() {
      this.getInfo()
    },
    saveData() {
      if (this.user.consent_personal) {
        const data = {
          user: this.user
        }
        setUserInfo(data)
          .then(response => {
            this.$message({
              message: 'Данные успешно сохренены.',
              type: 'success'
            })
          })
      }
    },
    getInfo() {
      getInfo()
        .then(response => {
          this.user = response.data
          if (this.user.email.substr(0, 8) === 'no_email') {
            this.user.email = ''
            this.email_write = false
          }
        })
      getSteadsList()
        .then(response => {
          this.steadsList = response.data
        })
    },
    findStead(query) {
      const data = {
        query: query
      }
      getSteadsList(data)
        .then(response => {
          this.steadsList = response.data
        })
    }
  }
}
</script>

<style scoped>
  .message{
    color: #bd0000;
    margin-bottom: 1em;
  }
  .socalConfirmed{
    opacity: 0.3;
  }
</style>
