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
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="profile">
        <MyProfile />
      </q-tab-panel>

      <q-tab-panel name="ban">
        <BanUserInfo :user-uid="authStore.user.uid" />
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

export default defineComponent({
  components: {
    MyProfile,
    BanUserInfo
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
