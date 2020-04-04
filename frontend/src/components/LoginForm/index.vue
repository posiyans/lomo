<template>
  <GuestForm v-if="roleGuest"/>
  <UserForm v-else/>
</template>

<script>
import GuestForm from './GuestForm.vue'
import UserForm from './UserForm.vue'
export default {
  name: 'LoginForm',
  components: {
    GuestForm,
    UserForm
  },
  data() {
    return {
    }
  },
  mounted() {
  },
  computed: {
    roleGuest() {
      if (this.$store.getters.roles && this.$store.getters.roles.includes('user')) {
        return false
      }
      return true
    }
  },
  methods: {

  }
}
</script>

<style scoped>

</style>

