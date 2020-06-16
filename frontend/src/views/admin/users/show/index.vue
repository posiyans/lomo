<template>
  <div style="padding: 0 20px">
    <UserInfo v-if="loading" v-model="user"/>
  </div>
</template>

<script>
import { fetchUserInfo } from '@/api/admin/user'
import { getSteadsList } from '@/api/user/stead.js'
import RoleAndPermision from '@/views/admin/users/components/permisions/index'
import AvatarUpload from '@/components/AvatarUpload/index'
import UserInfo from './../components/UserInfo.vue'

export default {
  components: {
    RoleAndPermision,
    AvatarUpload,
    UserInfo
  },
  data() {
    return {
      user: {},
      loading: false,
      steadsList: [],
      rules: {
        // type: [{ required: true, message: 'type is required', trigger: 'change' }],
        // timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        // title: [{ required: true, message: 'title is required', trigger: 'blur' }]
      },
    }
  },
  mounted() {
    this.fetchUser()
    this.fetchSteads()
  },
  methods: {
    fetchSteads() {
      getSteadsList()
        .then(response => {
          this.steadsList = response.data
        })
    },
    fetchUser() {
      console.log(this.$route.params.id)
      fetchUserInfo(this.$route.params.id)
        .then(response => {
          this.user = response.data.data
          this.loading = true
        })
    }
  }
}
</script>

<style scoped>

</style>
