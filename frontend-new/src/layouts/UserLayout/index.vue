<template>
  <q-layout view="lHh Lpr lFf" style="max-width: 90%; margin: 0 auto">
    <HeaderBlock />
    <MenuBlock />
    <q-page-container class="q-mt-sm" >
      <router-view />
    </q-page-container>
    <AcceptCookieDialog />
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import HeaderBlock from './components/HeaderBlock/index.vue'
import MenuBlock from './components/MenuBlock/index.vue'
import AcceptCookieDialog from 'components/AcceptCookieDialog/index.vue'

export default defineComponent({
  name: 'MainLayout',

  components: {
    HeaderBlock,
    MenuBlock,
    AcceptCookieDialog
  },

  setup () {
    const leftDrawerOpen = ref(false)

    return {
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
})
</script>
