<template>
  <div class="q-gutter-sm">
    <div v-for="(item, index) in roleAndPermissionStore.roles" :key="item.name" class="">
      <div class="row items-center q-col-gutter-xs">
        <div v-if="item.load" style="min-height: 3.2em; min-width: 3.2em;">
          <q-spinner-tail
            color="primary"
            size="2em"
          />
        </div>
        <div v-else>
          {{ ++index }}.
        </div>
        <div v-if="false" style="min-height: 3.2em;min-width: 3.2em;">
          <q-checkbox
            disable
            :model-value="activeUserStore.rolesArray.includes(item.name)"
            @update:model-value="changeRole($event, item)"
          />
        </div>
        <div>
          {{ item.display_name }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { changeRoleForUser } from 'src/Modules/Users/api/role-admin-api'
import { useActiveUserStore } from 'src/Modules/Users/stores/useActiveUserStore'
import { useRoleAndPermissionStore } from 'src/Modules/Users/stores/useRoleAndPermissionStore'

export default defineComponent({
  components: {},
  props: {},
  setup() {
    const activeUserStore = useActiveUserStore()
    const roleAndPermissionStore = useRoleAndPermissionStore()

    const changeRole = (val, item) => {
      roleAndPermissionStore.roles.forEach(role => {
        if (role.name === item.name) {
          role.load = true
        }
      })
      const data = {
        user_uid: activeUserStore.user.uid,
        role_name: item.name,
        action: 'delete'
      }
      if (val) {
        data.action = 'add'
        activeUserStore.roles.push(item)
      } else {
        let i = 0
        activeUserStore.roles.forEach(role => {
          if (role.name === item.name) {
            activeUserStore.roles.splice(i, 1)
          }
          i++
        })
      }
      changeRoleForUser(data)
        .then(res => {
          activeUserStore.getUserRole()
        })
        .finally(() => {
          roleAndPermissionStore.roles.forEach(role => {
            if (role.name === item.name) {
              role.load = false
            }
          })
        })
    }
    return {
      roleAndPermissionStore,
      activeUserStore,
      changeRole
    }
  }
})
</script>

<style scoped>

</style>
