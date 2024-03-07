<template>
  <div class="q-pa-md">
    <div class="row items-center">
      <div class="page-title">
        <UserAvatarImg :uid="activeUserStore.user.uid" style="width: 48px;" />
        {{ activeUserStore.user.last_name }}
        {{ activeUserStore.user.name }}
        {{ activeUserStore.user.middle_name }}
      </div>
      <div v-if="activeUserStore.user.owner" class="text-primary cursor-pointer" @click="toOwner(activeUserStore.user.owner.uid)">
        Собственник
        <div class="row ">
          <div v-for="stead in activeUserStore.user.owner.steads" :key="stead.id" class="" @click="toStead(stead.id)">
            <q-chip outline color="primary" text-color="white">
              уч. {{ stead.number }} {{ propFilter(stead.proportion) }}
            </q-chip>
          </div>
        </div>
      </div>
    </div>
    <div>
      <q-tabs
        v-model="data"
        dense
        class="text-black"
        active-color="primary"
        indicator-color="primary"
        align="left"
        narrow-indicator
      >
        <q-tab name="pers" label="Данные" />
        <q-tab v-if="authStore.checkPermission(['user-edit'])" name="roles" label="Роли" />
        <q-tab name="ban" label="Бан" />
        <q-tab name="comments" label="Комментарии" />
        <q-tab name="appeal" label="Обращения" />
        <q-tab v-if="authStore.checkPermission(['user-edit'])" name="actions" label="Действия" />
      </q-tabs>
      <q-separator />

      <q-tab-panels v-model="data" animated>
        <q-tab-panel name="pers">
          <ActiveUserData />
        </q-tab-panel>

        <q-tab-panel name="roles">
          <ActiveUserRolesAndPermissions />
        </q-tab-panel>

        <q-tab-panel name="ban">
          <BanUserInfo :user-uid="activeUserStore.user.uid" />
        </q-tab-panel>

        <q-tab-panel name="comments">
          <CommentsList v-if="activeUserStore.user.uid" :user-uid="activeUserStore.user.uid" />
        </q-tab-panel>
        <q-tab-panel name="appeal" class="q-pa-npne">
          <AppealList :user-uid="activeUserStore.user.uid" />
        </q-tab-panel>
        <q-tab-panel name="actions">
          <div v-if="!activeUserStore.user.email_verified_at" class="row items-center q-col-gutter-sm">
            <div>
              Повторное потверждение почты
            </div>
            <SendVerifyEmailBtn :user-uid="activeUserStore.userUid">
              <q-btn color="primary" label="Отправить код">
                <q-tooltip>
                  Email не подтвержден, Нажмите чтоб отправитть письмо с кодом подтверждения
                </q-tooltip>
              </q-btn>
            </SendVerifyEmailBtn>
          </div>
        </q-tab-panel>
      </q-tab-panels>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ActiveUserData from 'src/Modules/Users/components/ActiveUserData/index.vue'
import ActiveUserRolesAndPermissions from 'src/Modules/Users/components/ActiveUserRolesAndPermissions/index.vue'
import ActiveUserPermissions from 'src/Modules/Users/components/ActiveUserPermissions/index.vue'
import { useActiveUserStore } from 'src/Modules/Users/stores/useActiveUserStore'
import BanUserInfo from 'src/Modules/BanUsers/components/BanUserInfo/index.vue'
import CommentsList from 'src/Modules/Comments/components/CommentsList/index.vue'
import AppealList from 'src/Modules/Appeal/pages/AppealList/index.vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import UserAvatarImg from 'src/Modules/Avatar/components/UserAvatarImg/index.vue'
import SendVerifyEmailBtn from 'src/Modules/Auth/components/SendVerifyEmailBtn/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    SendVerifyEmailBtn,
    UserAvatarImg,
    UserAvatarByUid,
    ActiveUserData,
    ActiveUserRolesAndPermissions,
    ActiveUserPermissions,
    BanUserInfo,
    AppealList,
    CommentsList
  },
  props: {},

  setup() {
    const route = useRoute()
    const data = ref(route.query?.tab || 'pers')
    const authStore = useAuthStore()
    const router = useRouter()
    const activeUserStore = useActiveUserStore()
    onMounted(() => {

    })
    const toOwner = (uid) => {
      router.push('/admin/owner/show-info/' + uid)
    }
    const toStead = (id) => {
      router.push('/admin/stead/info/' + id)
    }
    const propFilter = (val) => {
      if (val < 100) {
        return '(' + val + '%)'
      }
      return ''
    }
    return {
      data,
      toOwner,
      authStore,
      propFilter,
      toStead,
      activeUserStore
    }
  }
})
</script>

<style scoped>

</style>
