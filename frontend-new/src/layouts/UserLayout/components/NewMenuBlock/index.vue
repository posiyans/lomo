/* eslint-disable */
<template>
  <div class="items-center q-col-gutter-sm q-pa-sm text-white" :class="classMenu">
    <div v-for="item in siteMenu.menu" :key="item.id" class="">
      <MenuItem v-if="item" :item="item" :vertical="props.vertical" />
    </div>
    <AdminPanel />
    <div v-if="siteMenu.loading" class="q-px-md">
      <q-spinner-dots
          color="white"
          size="2em"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import AdminPanel from 'layouts/UserLayout/components/NewMenuBlock/AdminPanel'
import { defineComponent, ref, computed, reactive } from 'vue'
import { useQuasar } from 'quasar'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/menu-store.js'
import MenuItem from './MenuItem'

export default defineComponent({
  components: {
    MenuItem,
    AdminPanel
  },
  props: {
    vertical: {
      type: Boolean,
      default: false
    }
  },
  setup (props) {
    const $q = useQuasar()
    const siteMenu = useSiteMenuStore()
    const classMenu = computed(() => {
      if (props.vertical) {
        return ''
      }
      return 'row'
    })
    return {
      siteMenu,
      classMenu,
      props
    }
  }
})
</script>

<style scoped>

</style>
