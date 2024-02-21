<template>
  <SiteMenu v-model="siteMenuStore.menu" :icon="icon" :first="first" />
  <SiteMenu v-if="isOwner" v-model="ownerMenu" :icon="icon" :first="first" />
</template>

<script>
import { computed, defineComponent } from 'vue'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenu'
import SiteMenu from 'src/components/SiteMenu/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { getMenu } from 'src/Modules/PersonalArea/js/PersonalAreaMenu'

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
    const ownerMenu = getMenu()
    const authStore = useAuthStore()
    const isOwner = computed(() => {
      return authStore.isOwner
    })
    return {
      siteMenuStore,
      isOwner,
      ownerMenu
    }
  }
})
</script>

<style scoped>

</style>
