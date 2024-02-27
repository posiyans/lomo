<template>
  <UserSteadsSelected>
    <template v-slot:default="{stead}">
      <SteadTab :stead-id="stead" />
    </template>
  </UserSteadsSelected>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import SteadTab from './components/SteadTab/index.vue'
import UserSteadsSelected from 'src/Modules/PersonalArea/components/UserSteadsSelected/index.vue'

export default defineComponent({
  components: {
    UserSteadsSelected,
    SteadTab
  },
  props: {},
  setup(props, { emit }) {

    return {}
  }
})
</script>

<style scoped>

</style>
