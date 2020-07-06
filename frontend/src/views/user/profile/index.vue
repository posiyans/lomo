6<template>
  <div>
    <el-card>
      <div class="article-preview-header">
        <h2>Профиль</h2>
      </div>
      <div class="article-preview-body" style="padding: 0 10px; max-width: 600px; margin: 0 auto" >
        <AvatarUpload v-model="user.avatar" :user_id="user.id"/>
      </div>
      <div class="article-preview-body" style="padding: 0 10px; max-width: 600px; margin: 0 auto" >
        <el-form
          ref="form"
          :model="form"
          label-width="220px"
          :label-position="labelPosition"
        >
          <el-form-item label="E-mail">
            <el-input v-model="user.email"  :readonly="email_write" placeholder="Укажите свой электронный ящик"></el-input>
          </el-form-item>
          <el-form-item label="Фамилия">
            <el-input v-model="user.last_name"></el-input>
          </el-form-item>
          <el-form-item label="Имя">
            <el-input v-model="user.name"></el-input>
          </el-form-item>
          <el-form-item label="Отчество">
            <el-input v-model="user.middle_name"></el-input>
          </el-form-item>
          <el-form-item label="Телефон">
            <el-input v-model="user.phone"></el-input>
          </el-form-item>
          <el-form-item label="Адрес регистрации/почтовый адрес">
            <el-input type="textarea" v-model="user.adres"></el-input>
          </el-form-item>
          <div class="message">Все поля обязтельны для заполнения, и необходимы для вашей идентификации администрацией сайта </div>
          <el-form-item label="Собственник">
            <el-checkbox v-model="owner" name="type"></el-checkbox>
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
              :loading="loading">
              <el-option
                v-for="item in steadsList"
                :key="item.id"
                :label="item.number"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Cогласен ">
            <el-tooltip class="item" effect="light" content="Cогласен на обработку персональных данных и с условиями пользовательского соглашения" placement="top">
            <el-checkbox
              v-model="user.consent_personal"
              label="на обработку персональных данных"
              name="type"
            />
            </el-tooltip>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="saveData" :disabled="!user.consent_personal">Сохранить</el-button>
            <el-button>Отменить</el-button>
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
    AvatarUpload,
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
    padding-left: 50px;
  }
</style>
