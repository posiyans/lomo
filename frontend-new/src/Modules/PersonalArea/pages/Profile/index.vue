<template>
  <q-card>

    <q-tabs
      v-model="tab"
      align="left"
      class="text-teal"
      :breakpoint="0"
    >
      <q-tab name="profile" label="Мой профиль" />
      <q-tab name="ban" label="Ограничения" />
      <q-tab name="appeal" label="Обращения" />
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="profile">
        <MyProfile />
      </q-tab-panel>

      <q-tab-panel name="ban">
        <BanUserInfo :user-uid="authStore.user.uid" />
      </q-tab-panel>
      <q-tab-panel name="appeal">
        <div v-if="authStore.user.email_verified_at">
          <UserAppealList :user-uid="authStore.user.uid" />
        </div>
        <div v-else class="page-title text-red text-center q-pa-lg">
          Для создания обращения подтвердите свою почту
        </div>
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import MyProfile from 'src/Modules/Auth/page/MyProfile/index.vue'
import BanUserInfo from 'src/Modules/BanUsers/components/BanUserInfo/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import UserAppealList from 'src/Modules/Appeal/pages/UserApealList/index.vue'

export default defineComponent({
  components: {
    MyProfile,
    BanUserInfo,
    UserAppealList
  },
  props: {},
  setup(props, { emit }) {
    const tab = ref('profile')
    const router = useRouter()
    const route = useRoute()
    const authStore = useAuthStore()
    onMounted(() => {

    })
    return {
      tab,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
