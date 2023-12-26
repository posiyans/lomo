<template>
  <q-layout view="lHh Lpr lFf" class="user-main-layout">
    <HeaderBlock />
    <MenuBlock />
    <q-page-container class="">
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

  setup() {
    const leftDrawerOpen = ref(false)

    return {
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
})
</script>

<style scoped lang="scss">
.q-page-container {
  background-image: url("src/assets/bg-img.jpg");
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: top;
  background-color: #e8f3f7;
  min-height: 100vh;

  div {

    min-height: calc(100vh - 100px);
    //min-height: 100%;
    background-color: rgba(255, 255, 255, .85);
    padding: 5px;
    width: 90%;
    margin: 0 auto;
    @media only screen and (max-width: 599px) {
      min-height: calc(100vh - 50px);
      width: 100%;
      margin: 0;
      padding: 0 10px;
    }
    @media only screen and (max-width: 400px) {
      padding: 0 5px;
    }
  }
}

.user-main-layout {

  //width: 90%;
  //margin: 0 auto;
  //@media only screen and (max-width: 599px) {
  //  width: 100%;
  //  margin: 0;
  //  padding: 0 10px;
  //}
  //@media only screen and (max-width: 400px) {
  //  padding: 0 5px;
  //}
}
</style>
