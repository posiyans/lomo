<template>
  <div>
    <div v-if="authStore.loading" class="q-mr-md">
      <q-spinner-dots
        color="primary"
        size="2em"
      />
    </div>
    <div v-else>
      <div v-if="authStore.is_guest" class="row items-center q-col-gutter-sm">
        <LoginBtn />
        <RegisterBtn />
      </div>
      <div v-else class="">
        <q-btn-dropdown flat no-caps>
          <template v-slot:label>
            <div class="row items-center no-wrap">
              <UserAvatarByUid :uid="authStore.user.uid" size="32px" class="q-mr-xs" />
              <div class="text-center ellipsis" style="max-width: 80px;">
                {{ authStore.user.name }}
              </div>
            </div>
          </template>
          <q-list>
            <q-item clickable v-close-popup to="/profile">
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
import { computed, defineComponent } from 'vue'
import LoginBtn from 'src/Modules/Auth/components/LoginBtn/index.vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import RegisterBtn from 'src/Modules/Auth/components/RegisterBtn/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore.js'
import LogoutBtn from 'src/Modules/Auth/components/LogoutBtn/index.vue'

export default defineComponent({
  components: {
    LoginBtn,
    RegisterBtn,
    LogoutBtn,
    UserAvatarByUid
  },
  setup() {
    const authStore = useAuthStore()
    const admin = computed(() => {
      return authStore.permissions.includes('access-admin-panel')
    })
    return {
      authStore,
      admin
    }
  }
})
</script>

<style scoped>

</style>
