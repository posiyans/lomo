<template>
    <q-layout view="lHh Lpr lff" container style="min-height: 100vh;">
      <q-header>
        <q-toolbar>
          <q-btn flat @click="miniState = !miniState" round dense icon="menu" />
          <q-toolbar-title>Панель управления</q-toolbar-title>
        </q-toolbar>
      </q-header>

      <q-drawer
          v-model="drawer"
          show-if-above
          :mini="miniState"
          :width="200"
          :breakpoint="500"
          bordered
          class="bg-grey-3"
      >
        <q-scroll-area class="fit">
          <AdminMenu />
        </q-scroll-area>
      </q-drawer>

      <q-page-container>
        <router-view />
      </q-page-container>
    </q-layout>
</template>
<script>
import { defineComponent, ref } from 'vue'
import AdminMenu from 'layouts/AdminLayout/components/AdminMenu'

export default defineComponent({
  name: 'AdminLayout',
  components: {
    AdminMenu
  },
  setup () {
    return {
      drawer: ref(false),
      miniState: ref(false)
    }
  }
})
</script>
