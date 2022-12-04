<template>
  <q-drawer
      v-model="leftDrawerOpen"
      bordered
      style="background-color: #37474f"
  >
    <div>
      <NewMenuBlock vertical/>
    </div>
  </q-drawer>
</template>

<script>
import { defineComponent } from 'vue'
import { leftDrawerOpen } from 'src/layouts/UserLayout/hooks/drawer.js'
import NewMenuBlock from 'layouts/UserLayout/components/NewMenuBlock/index.vue'

export default defineComponent({
  components: {
    NewMenuBlock
  },
  setup () {
    return {
      leftDrawerOpen
    }
  }
})
</script>
