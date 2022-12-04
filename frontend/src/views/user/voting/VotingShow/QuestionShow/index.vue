<template>
  <component :is="VotingType" v-model="voting" v-loading="loading" @changeResult="getVoting" />
</template>

<script>
import PublicVoting from './components/PublicVoting'
import OwnerVoting from './components/OwnerVoting'

export default {
  name: 'UserVotingIndex',
  components: {
    PublicVoting,
    OwnerVoting
  },
  props: {
    voting: {
      type: Object,
      required: true
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
