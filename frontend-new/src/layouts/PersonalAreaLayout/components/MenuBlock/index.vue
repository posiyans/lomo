<template>
  <q-drawer
    v-model="leftDrawerOpen"
    bordered
    class="bg-blue-grey-2"
  >
    <div>
      <UserMenu icon />
    </div>
  </q-drawer>
</template>

<script>
import { defineComponent } from 'vue'
import { leftDrawerOpen } from 'src/layouts/UserLayout/hooks/drawer.js'
import UserMenu from 'src/layouts/UserLayout/components/UserMenu/index.vue'

export default defineComponent({
  components: {
    UserMenu
  },
  setup() {
    return {
      leftDrawerOpen
    }
  }
})
</script>
