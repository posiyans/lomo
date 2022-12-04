<template>
  <div>
    <div v-if="roleGuest" class="flex">
      <div>
        <LoginModal />
      </div>
      <div>
        <RegisterModal />
      </div>
    </div>
    <div v-else class="flex">
      <div class="ml2 mr2 mr4-ns">
        <el-dropdown @command="handleCommand">
          <div class="flex items-center">
            <div class="fw6 pr2">
              {{ user.name }}
            </div>
            <div class="header-avatar-wrapper">
              <img :src="avatarUrl">
            </div>
            <i class="el-icon-arrow-down el-icon--right" />
          </div>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item v-if="admin" command="adminPanel">Админ панель</el-dropdown-item>
            <el-dropdown-item command="profile">Профиль</el-dropdown-item>
            <el-dropdown-item command="logout" divided>Выход</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <el-dialog
      title="Регистрация"
      v-model:visible="showRegisterForm"
      width="300px"
      center
    >
      <div>
        <div>Зарегистрироваться и войти через</div>
        <div @click="loginVK"><img src="/images/vk-banner.png" width="100px"></div>

      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import LoginModal from './LoginModal'
import RegisterModal from './RegisterModal'

export default {
  name: 'HeaderLink',
  components: {
    LoginModal,
    RegisterModal
  },
  data () {
    return {
      form: {
        email: '',
        password: '',
        remember: false
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
    roleGuest () {
      return this.$store.getters.roles.includes('guest')
    },
    admin () {
      if (this.$store.getters.user.allPermissions.includes('access-admin-panel')) {
        return true
      }
      return false
    },
    cachedViews () {
      // console.log('this.$store.state.tagsView.cachedViews')
      // console.log(this.$store.state.tagsView.cachedViews)
      return this.$store.state.tagsView.cachedViews
    },
    key () {
      return this.$route.path
    },
    avatarUrl () {
      if (this.$store.getters.user.avatar) {
        if (this.$store.getters.user.avatar[0] === '/') {
          return process.env.VUE_APP_BASE_API + this.$store.getters.user.avatar
        }
        return this.$store.getters.user.avatar
      } else {
        return process.env.VUE_APP_BASE_API + '/images/default-avatar.jpg'
      }
    }
  },
  methods: {
    async handleCommand (command) {
      if (command === 'logout') {
        // console.log('logout')
        await this.$store.dispatch('user/logout')
        // await this.$store.dispatch('user/getInfo')
        // this.key += 1
      }
      if (command === 'profile') {
        this.$router.push('/user/profile')
      }
      if (command === 'adminPanel') {
        this.$router.push('/admin-article/list')
      }
    },
    async loginVK () {
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
    test () {
      console.log('close!!!!!!!!!!!')
    },
    login () {
      this.$store.dispatch('user/login', this.form)
        .then(() => {
          this.$router.push({ path: this.redirect || '/', query: this.otherQuery })
          this.showLoginForm = false
        })
        .catch(() => {
          this.loading = false
        })
    }
  }
}
</script>
