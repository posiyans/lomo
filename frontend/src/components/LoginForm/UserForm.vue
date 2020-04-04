<template>
  <RightCard header="Добро пожаловать" class="right-welcome">
    <template>
      <div align="center" class="plugin-welcome">
        <div class="welcome-avatar-wrapper">
          <img :src="avatarUrl+'?imageView2/1/w/80/h/80'">
        </div>
        <div class="center">{{ user.last_name }} {{ user.name }}</div>
        <div>{{ user.middle_name }}</div>
<!--        <div>-->
<!--          <el-button type="warning" size="mini" plain>Сообщения</el-button>-->
<!--        </div>-->
<!--        <div>-->
<!--          <el-button type="success" size="mini" plain>Показания счетчиков</el-button>-->
<!--        </div>-->
        <div>
          <el-button type="text" @click="toProfile">Профиль</el-button>
        </div>
        <el-button type="primary" @click="logout">Выйти</el-button>
      </div>
    </template>
  </RightCard>
</template>

<script>
import RightCard from '@/components/RightCard'
import { mapGetters } from 'vuex'
import store from '../../store'
export default {
  name: 'LoginForm',
  components: {
    RightCard
  },
  data() {
    return {
      form: {
        name: '',
        rename: false
      },
    }
  },
  mounted() {
    console.log(this.$store.getters.roles)
    console.log(this.roleGuest)
  },
  computed: {
    ...mapGetters([
      'sidebar',
      'avatar',
      'device',
      'user'
    ]),
    avatarUrl() {
      if (this.$store.getters.user.avatar) {
        if (this.$store.getters.user.avatar[0] === '/') {
          return process.env.VUE_APP_BASE_API + this.$store.getters.user.avatar
        }
        return this.$store.getters.user.avatar
      } else {
        return process.env.VUE_APP_BASE_API + '/../images/default-avatar.jpg'
      }
    },
    roleGuest() {
      return this.$store.getters.roles.includes('guest')
    }
  },
  methods: {
    toProfile(){
      this.$router.push('/user/profile')
    },
    async logout() {
      console.log('user logouut')
      await this.$store.dispatch('user/logout')
      // await this.$store.dispatch('user/getInfo')
      // await this.$store.dispatch('permission/getMenu')
    }
  }
}
</script>

<style scoped>

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

  .login-form a{
    /*position: relative;*/
    /*top: 20px;*/
    float: right;
    font-size: 12px;
    margin-top: -28px;
    margin-right: 10px;

  }

  .right-welcome >>> .avatar-wrapper {
    margin-top: 5px;
    position: relative;

    .user-avatar {
      cursor: pointer;
      width: 40px;
      height: 40px;
      border-radius: 10px;
    }

    .el-icon-caret-bottom {
      cursor: pointer;
      position: absolute;
      right: -20px;
      top: 25px;
      font-size: 12px;
    }
  }

  /*.header {*/
  /*  line-height: 60px;*/
  /*}*/
  /* .layout-header {*/
  /*   background-color: #00084c;*/
  /*   color: #fff;*/
  /* }*/

</style>


