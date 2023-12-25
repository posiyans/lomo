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
        <q-tab name="roles" label="Роли" />
        <q-tab name="ban" label="Бан" />
        <q-tab name="comments" label="Комментарии" />
        <q-tab name="appeal" label="Обращения" />
        <q-tab name="actions" label="Действия" />
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
        <q-tab-panel name="appeal">
          <AppealList :user-uid="activeUserStore.user.uid" />
        </q-tab-panel>
        <q-tab-panel name="actions">
          <div v-if="!activeUserStore.user.email_verified_at">
            Повторное потверждение почты
            <el-button type="primary" size="small" @click="sentToken">
              Отправить
            </el-button>
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
import { sendVerifyMailToken } from 'src/Modules/Users/api/user-admin-api'
import BanUserInfo from 'src/Modules/BanUsers/components/BanUserInfo/index.vue'
import CommentsList from 'src/Modules/Comments/components/CommentsList/index.vue'
import AppealList from 'src/Modules/Appeal/pages/AppealList/index.vue'
import UserAvatarByUid from 'src/Modules/Avatar/components/UserAvatarByUid/index.vue'
import UserAvatarImg from 'src/Modules/Avatar/components/UserAvatarImg/index.vue'

export default defineComponent({
  components: {
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
    const sentToken = () => {
      sendVerifyMailToken(activeUserStore.userId)
        .then(response => {
          if (response.data.status) {
            this.$message({
              message: response.data.data,
              type: 'success'
            })
          } else {
            this.$message({
              message: response.data.data,
              type: 'error'
            })
          }
        })
    }
    return {
      data,
      toOwner,
      propFilter,
      toStead,
      sentToken,
      activeUserStore
    }
  }
})
</script>

<style scoped>

</style>
