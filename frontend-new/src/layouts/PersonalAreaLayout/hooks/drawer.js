import { ref } from 'vue'

const leftDrawerOpen = ref(false)

const toggleLeftDrawer = function () {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
export {
  leftDrawerOpen,
  toggleLeftDrawer
}
