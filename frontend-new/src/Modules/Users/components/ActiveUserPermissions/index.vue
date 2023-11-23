<template>
  <div>
    <div v-for="item in roleAndPermissionStore.permissions" :key="item.name" :class="{ 'text-grey': !authStore.permissions.includes(item.name)}">
      <div class="row items-center q-col-gutter-xs">
        <div v-if="item.load" style="min-height: 3.2em; min-width: 3.2em;">
          <q-spinner-tail
            color="primary"
            size="2em"
          />
        </div>
        <div v-else style="min-height: 3.2em; min-width: 3.2em;">
          <q-checkbox
            :model-value="activeUserStore.permissionsArray.includes(item.name)"
            :disable="!authStore.permissions.includes(item.name)"
            @update:model-value="changePermission($event, item)"
          />
        </div>
        <div>
          {{ item.display_name }}
        </div>
        <div class="text-grey q-ml-sm">
          {{ item.name }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { changePermissionForUser } from 'src/Modules/Users/api/role-admin-api'
import { useActiveUserStore } from 'src/Modules/Users/stores/useActiveUserStore'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { useRoleAndPermissionStore } from 'src/Modules/Users/stores/useRoleAndPermissionStore'

export default defineComponent({
  components: {},
  props: {},
  setup() {
    const activeUserStore = useActiveUserStore()
    const roleAndPermissionStore = useRoleAndPermissionStore()
    const authStore = useAuthStore()
    const changePermission = (val, item) => {
      roleAndPermissionStore.permissions.forEach(role => {
        if (role.name === item.name) {
          role.load = true
        }
      })
      const data = {
        user_uid: activeUserStore.user.uid,
        permission_name: item.name,
        action: 'delete'
      }
      if (val) {
        data.action = 'add'
        activeUserStore.permissions.push(item)
      } else {
        let i = 0
        activeUserStore.permissions.forEach(role => {
          if (role.name === item.name) {
            activeUserStore.permissions.splice(i, 1)
          }
          i++
        })
      }
      changePermissionForUser(data)
        .then(res => {
          activeUserStore.getUserRole()
        })
        .finally(() => {
          roleAndPermissionStore.permissions.forEach(role => {
            if (role.name === item.name) {
              role.load = false
            }
          })
        })
    }

    return {
      changePermission,
      roleAndPermissionStore,
      activeUserStore,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
