<template>
  <GuestForm v-if="roleGuest" />
</template>

<script>
import GuestForm from './GuestForm.vue'
export default {
  name: 'LoginForm',
  components: {
    GuestForm
  },
  computed: {
    roleGuest() {
      return !(this.$store.getters.roles && this.$store.getters.roles.includes('user'))
    }
  }
}
</script>

<style scoped>

</style>

