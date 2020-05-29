<template>
   <component  v-loading="loading" v-bind:is="VotingType" v-model="voting" v-on:changeResult="getVoting"></component>
</template>

<script>
import PublicVoting from './components/PublicVoting.vue'
import OwnerVoting from './components/OwnerVoting.vue'

export default {
  name: 'UserVotingIndex',
  components: {
    PublicVoting,
    OwnerVoting
  },
  props: {
    voting: {
      type: Object
    }
  },
  data() {
    return {
      // voting: {},
      loading: false
    }
  },
  computed: {
    VotingType() {
      if (this.voting.type === 'public') {
        return PublicVoting
      }
      return OwnerVoting
    }
  },
  methods: {
    getVoting() {
      this.$emit('changeResult')
    }
  }
}
</script>

<style scoped>
</style>
