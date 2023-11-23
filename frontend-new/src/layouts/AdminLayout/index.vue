<template>
  <q-layout view="lHh Lpr lff" container style="min-height: 100vh;">
    <q-header>
      <q-toolbar>
        <q-btn flat @click="drawer = !drawer" round dense icon="menu" />
        <q-toolbar-title>{{ primaryStore.pageName }}</q-toolbar-title>
        <div>
          <q-btn-dropdown flat no-caps>
            <template v-slot:label>
              <div class="row items-center no-wrap">
                <UserAvatarByUid :uid="authStore.user.uid" size="40px" class="q-mr-xs" />
                <div class="text-center">
                  {{ authStore.user.name }}
                </div>
              </div>
            </template>
            <q-list>
              <q-item clickable v-close-popup to="/auth/profile">
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
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="drawer"
      show-if-above
      :mini="!drawer || miniState"
      :breakpoint="500"
      bordered
      class="bg-blue-grey-2"
    >
      <q-scroll-area class="fit">
        <AdminMenu />
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>
<script>
import { defineComponent, ref } from 'vue'
import AdminMenu from 'layouts/AdminLayout/components/AdminMenu'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import LogoutBtn from 'src/Modules/Auth/components/LogoutBtn/index.vue'
import { usePrimaryStore } from 'stores/parimary-store'

export default defineComponent({
  name: 'AdminLayout',
  components: {
    AdminMenu,
    LogoutBtn,
    UserAvatarByUid
  },
  setup() {
    const authStore = useAuthStore()
    const primaryStore = usePrimaryStore()
    return {
      drawer: ref(false),
      miniState: ref(false),
      primaryStore,
      authStore
    }
  }
})
</script>
