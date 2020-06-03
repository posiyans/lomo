<template>
  <div>
  <component  v-loading="loading" v-bind:is="VotingType" v-model="voting" @changeResult="getVoting"></component>

  </div>
</template>

<script>
import PublicVoting from './result/Public/index.vue'
import { fetchAdminVoting } from '@/api/admin/voting/'

export default {
  name: 'AdminVotingResultIndex',
  components: {
    PublicVoting,
  },
  data() {
    return {
      voting: {},
      loading: false
    }
  },
  computed: {
    VotingType() {
      if (this.voting.type === 'public') {
        return PublicVoting
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
