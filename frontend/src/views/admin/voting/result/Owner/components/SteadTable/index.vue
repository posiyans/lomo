<template>
  <div class="app-container">
    <TableResult v-if="loading" :steads="voting.questions" />
  </div>
</template>

<script>
import { fetchUserVoting } from '@/api/user/voting'

import TableResult from './components/TableResult'

export default {
  components: {
    TableResult
  },
  data() {
    return {
      id: this.$route.params && this.$route.params.id,
      loading: false
    }
  },
  created() {
    this.getVoting()
  },
  methods: {
    getVoting() {
      this.listLoading = true
      fetchUserVoting(this.id).then(response => {
        this.voting = response.data.data
        this.loading = true
        // this.total = response.data.meta.total
      })
    }
  }
}
</script>

<style scoped>

</style>
