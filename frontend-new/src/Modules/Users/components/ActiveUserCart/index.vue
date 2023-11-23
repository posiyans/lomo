<template>
  <div class="q-pa-md">
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

export default defineComponent({
  components: {
    ActiveUserData,
    ActiveUserRolesAndPermissions,
    ActiveUserPermissions,
    BanUserInfo,
    CommentsList
  },
  props: {},

  setup() {
    const data = ref('pers')
    const router = useRouter()
    const route = useRoute()
    const activeUserStore = useActiveUserStore()
    onMounted(() => {

    })
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
      sentToken,
      activeUserStore
    }
  }
})
</script>

<style scoped>

</style>
