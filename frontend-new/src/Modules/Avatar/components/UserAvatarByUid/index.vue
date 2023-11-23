<template>
  <q-avatar :rounded="rounded" :square="square" :size="size">
    <UserAvatarImg :uid="uid">
      <slot></slot>
    </UserAvatarImg>
  </q-avatar>
</template>

<script>
import UserAvatarImg from 'src/Modules/Avatar/components/UserAvatarImg/index.vue'

export default {
  components: {
    UserAvatarImg
  },
  props: {
    uid: {
      type: [String, Number],
      required: true
    },
    size: {
      type: String,
      default: ''
    },
    rounded: {
      type: Boolean,
      default: false
    },
    square: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped>

</style>
