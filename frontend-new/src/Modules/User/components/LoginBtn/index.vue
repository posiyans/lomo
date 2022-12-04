<template>
  <div class="ml2 mr2 mr4-ns">
    <el-button class="text-weight-bold" @click="showLoginForm = !showLoginForm">Войти</el-button>
    <el-dialog
      title="Войти на сайт"
      :fullscreen="!!mobile"
      v-model="showLoginForm"
      width="600px"
      center
    >
      <LoginForm @close="showLoginForm = false"/>
      <div class="q-pa-sm text-right">
        <el-button type="primary" @click="showLoginForm = false">Закрыть</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import LoginForm from 'src/Modules/User/components/LoginForm/index.vue'
export default {
  components: {
    LoginForm
  },
  data () {
    return {
      showLoginForm: false
    }
  },
  computed: {
    mobile () {
      return this.$q.platform.is.mobile
    }
  }
}
</script>

<style scoped>

</style>
