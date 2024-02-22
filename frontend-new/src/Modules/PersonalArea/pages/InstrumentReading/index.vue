<template>
  <div>
    <UserSteadsSelected>
      <template v-slot:default="{stead}">
        <ForStead :stead-id="stead" />
      </template>
    </UserSteadsSelected>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import ForStead from './ForStead.vue'
import UserSteadsSelected from 'src/Modules/PersonalArea/components/UserSteadsSelected/index.vue'

export default defineComponent({
  components: {
    ForStead,
    UserSteadsSelected
  },
  props: {},
  setup(props, { emit }) {
    return {}
  }
})
</script>

<style scoped lang='scss'>

</style>

