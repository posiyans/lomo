<template>
  <q-card>

    <q-tabs
      v-model="tab"
      align="left"
      class="text-teal"
      :breakpoint="0"
    >
      <q-tab name="user" label="Пользователь" />
      <q-tab v-if="isOwner" name="owner" label="Собственник" />
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="user">
        <MyProfile />
      </q-tab-panel>
      <q-tab-panel name="owner">
        <ShowOwnerInfo :owner-uid="authStore.user.owner.uid" />
      </q-tab-panel>

    </q-tab-panels>
  </q-card>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import MyProfile from 'src/Modules/Auth/page/MyProfile/index.vue'
import BanUserInfo from 'src/Modules/BanUsers/components/BanUserInfo/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import UserAppealList from 'src/Modules/Appeal/pages/UserApealList/index.vue'
import ShowOwnerInfo from 'src/Modules/Owner/components/ShowOwnerInfo/index.vue'

export default defineComponent({
  components: {
    MyProfile,
    ShowOwnerInfo,
    BanUserInfo,
    UserAppealList
  },
  props: {},
  setup(props, { emit }) {
    const tab = ref('user')
    const router = useRouter()
    const route = useRoute()
    const authStore = useAuthStore()
    const isOwner = computed(() => {
      return authStore.isOwner
    })
    onMounted(() => {
      if (isOwner.value) {
        tab.value = 'steads'
      }
    })
    return {
      tab,
      isOwner,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
