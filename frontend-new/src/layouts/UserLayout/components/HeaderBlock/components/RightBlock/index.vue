<template>
  <div>
    <div v-if="userStore.loading" class="q-mr-md">
      <q-spinner-dots
          color="primary"
          size="2em"
      />
    </div>
    <div v-else>
      <div v-if="userStore.is_guest" class="row items-center q-col-gutter-sm">
        <LoginBtn />
        <RegisterBtn />
      </div>
      <div v-else class="">
        <q-btn-dropdown flat no-caps>
          <template v-slot:label>
            <div class="row items-center no-wrap">
              <UserAvatar :uid="userStore.user.uid" size="40px" class="q-mr-xs"/>
              <div class="text-center">
                {{userStore.user.name}}
              </div>
            </div>
          </template>
          <q-list>
            <q-item clickable v-close-popup to="/user/profile">
              <q-item-section>
                <q-item-label>Профиль</q-item-label>
              </q-item-section>
            </q-item>

            <q-item v-if="admin" clickable v-close-popup to="/admin">
              <q-item-section>
                <q-item-label>Админ панель</q-item-label>
              </q-item-section>
            </q-item>
            <LogoutBtn />
          </q-list>
        </q-btn-dropdown>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue'
import LoginBtn from 'src/Modules/User/components/LoginBtn/index.vue'
import RegisterBtn from 'src/Modules/User/components/RegisterBtn/index.vue'
import { useUserStore } from 'src/Modules/User/store/user-store.js'
import LogoutBtn from 'src/Modules/User/components/LogoutBtn/index.vue'
import UserAvatar from 'src/Modules/Avatar/components/UserAvatar/index.vue'

export default defineComponent({
  components: {
    LoginBtn,
    RegisterBtn,
    LogoutBtn,
    UserAvatar
  },
  setup () {
    const userStore = useUserStore()
    const admin = computed(() => {
      return userStore.permissions.includes('access-admin-panel')
    })
    return {
      userStore,
      admin
    }
  }
})
</script>

<style scoped>

</style>
