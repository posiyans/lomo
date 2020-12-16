<template>
  <div>
    <component :is="VotingType" v-model="voting" v-loading="loading" @changeResult="getVoting" />

  </div>
</template>

<script>
import PublicVoting from './Public/index.vue'
import OwnerVoting from './Owner/index.vue'
import { fetchAdminVoting } from '@/api/admin/voting/'

export default {
  name: 'AdminVotingResultIndex',
  components: {
    PublicVoting,
    OwnerVoting
  },
  data() {
    return {
      voting: {},
      loading: false
    }
  },
  computed: {
    VotingType() {
      if (this.voting.type === 'owner') {
        return OwnerVoting
      }
      return PublicVoting
    }
  },
  mounted() {
    this.fetchVoting()
  },
  methods: {
    getVoting() {
      this.$emit('changeResult')
    },
    fetchVoting() {
      const id = this.$route.params && this.$route.params.id
      // console.log(id)
      fetchAdminVoting(id)
        .then(response => {
          this.voting = response.data.data
          this.loading = false
        })
    }
  }
}
</script>

<style scoped>
</style>
