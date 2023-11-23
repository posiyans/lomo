<template>
  <SiteMenu v-model="siteMenuStore.menu" :icon="icon" :first="first" />
</template>

<script>
import { defineComponent } from 'vue'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenu'
import SiteMenu from 'src/components/SiteMenu/index.vue'

export default defineComponent({
  components: {
    SiteMenu
  },
  props: {
    icon: {
      type: Boolean,
      default: false
    },
    first: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    const siteMenuStore = useSiteMenuStore()
    return {
      siteMenuStore
    }
  }
})
</script>

<style scoped>

</style>
