<template>
    <q-header>
      <q-toolbar class="bg-cyan-2 text-indigo-10">
        <q-btn
            flat
            dense
            round
            icon="menu"
            aria-label="Menu"
            class="xs"
            @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          <div class="row items-center no-wrap">
            <SiteName class="cursor-pointer" @click="toHome" />
            <TemperWidget class="q-ml-sm"/>
          </div>
        </q-toolbar-title>

        <div>
          <RightBlock />
        </div>
      </q-toolbar>
      <q-toolbar class="bg-blue-grey-9 text-white xs-hide">
        <NewMenuBlock />
      </q-toolbar>
    </q-header>
</template>

<script>
import { defineComponent } from 'vue'
import { toggleLeftDrawer } from 'src/layouts/UserLayout/hooks/drawer.js'
import SiteName from 'layouts/UserLayout/components/SiteName/index.vue'
import TemperWidget from 'src/Modules/Temperature/components/TemperWidget/index.vue'
import RightBlock from './components/RightBlock/index.vue'
import { useRouter } from 'vue-router'
import NewMenuBlock from 'layouts/UserLayout/components/NewMenuBlock'
export default defineComponent({
  components: {
    SiteName,
    TemperWidget,
    RightBlock,
    NewMenuBlock
  },
  setup () {
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
