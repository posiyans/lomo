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
      <q-tab-panel name="profile" class="q-pa-none">
        <MyProfile />
      </q-tab-panel>
      <q-tab-panel name="ban">
        <BanUserInfo :user-uid="authStore.user.uid" />
      </q-tab-panel>
      <q-tab-panel name="appeal">
        <div v-if="authStore.user.email_verified_at">
          <AppealList :user-uid="authStore.user.uid" :key="key">
            <AddAppealBtn v-if="authStore.user.uid" :user-uid="authStore.user.uid" @success="reloadData" />
          </AppealList>
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
import { defineComponent, ref } from 'vue'
import MyProfile from 'src/Modules/Auth/page/MyProfile/index.vue'
import BanUserInfo from 'src/Modules/BanUsers/components/BanUserInfo/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import AddAppealBtn from 'src/Modules/Appeal/components/AddAppealBtn/index.vue'
import AppealList from 'src/Modules/Appeal/components/AppealList/index.vue'

export default defineComponent({
  components: {
    MyProfile,
    BanUserInfo,
    AppealList,
    AddAppealBtn
  },
  props: {},
  setup(props, { emit }) {
    const tab = ref('profile')
    const authStore = useAuthStore()
    const key = ref(1)
    const reloadData = (val) => {
      key.value++
    }
    return {
      tab,
      authStore,
      reloadData,
      key
    }
  }
})
</script>

<style scoped>

</style>
