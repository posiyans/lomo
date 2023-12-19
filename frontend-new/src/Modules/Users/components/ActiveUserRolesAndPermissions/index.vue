<template>
  <div>
    <div class="row">
      <div class="col-6">
        <div class="text-weight-bold">
          Роли
        </div>
        <ActiveUserRoles class="q-pa-sm" />
      </div>
      <div>
        <div class="text-weight-bold">
          Разрешения
        </div>
        <ActiveUserPermissions />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted } from 'vue'
import { useActiveUserStore } from 'src/Modules/Users/stores/useActiveUserStore'
import ActiveUserRoles from 'src/Modules/Users/components/ActiveUserRoles/index.vue'
import ActiveUserPermissions from 'src/Modules/Users/components/ActiveUserPermissions/index.vue'
import { useRoleAndPermissionStore } from 'src/Modules/Users/stores/useRoleAndPermissionStore'

export default defineComponent({
  components: {
    ActiveUserRoles,
    ActiveUserPermissions
  },
  props: {},
  setup() {
    const activeUserStore = useActiveUserStore()
    const roleAndPermissionStore = useRoleAndPermissionStore()

    onMounted(() => {
      roleAndPermissionStore.getRolesAndPermissions()
      activeUserStore.getUserRole()
    })
    return {}
  }
})
</script>

<style scoped>

</style>
