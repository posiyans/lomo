<template>
  <q-header>
    <q-toolbar class="bg-cyan-2 text-indigo-10">
      <q-btn
        flat
        round
        icon="menu"
        aria-label="Menu"
        class="xs"
        @click="toggleLeftDrawer"
      />

      <q-toolbar-title>
        <div class="row items-center no-wrap">
          <SiteName class="cursor-pointer" @click="toHome" />
          <TemperWidget class="q-ml-sm" />
        </div>
      </q-toolbar-title>
      <div class="user-menu-1">
        <UserMenu first />
      </div>
      <q-space />
      <div>
        <SearchBtn />
      </div>
      <div>
        <RightBlock />
      </div>
    </q-toolbar>
    <q-toolbar inset class="bg-blue-grey-9 text-white xs-hide user-menu-2">
      <UserMenu first />
    </q-toolbar>
  </q-header>
  <div>

  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { toggleLeftDrawer } from 'src/layouts/UserLayout/hooks/drawer.js'
import SiteName from 'layouts/UserLayout/components/SiteName/index.vue'
import TemperWidget from 'src/Modules/Weather/componets/TemperWidget/index.vue'
import RightBlock from './components/RightBlock/index.vue'
import { useRouter } from 'vue-router'
import UserMenu from 'src/layouts/UserLayout/components/UserMenu/index.vue'
import SearchBtn from 'src/Modules/Search/components/SearchBtn/index.vue'

export default defineComponent({
  components: {
    SiteName,
    TemperWidget,
    RightBlock,
    UserMenu,
    SearchBtn
  },
  setup() {
    const router = useRouter()
    const toHome = () => {
      router.push('/')
    }
    return {
      toggleLeftDrawer,
      toHome
    }
  }
})
</script>

<style scoped lang="scss">
.user-menu-1 {
  //display: none;
  @media only screen and (max-width: 960px) {
    display: none;
  }
}

.user-menu-2 {
  @media only screen and (min-width: 961px) {
    display: none;
  }
}
</style>
