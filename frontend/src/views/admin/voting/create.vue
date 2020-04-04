<template>
  <voting-detail :is-edit="false" />
</template>

<script>
import VotingDetail from './components/VotingDetail.vue'

export default {
  name: 'CreateVoting',
  components: { VotingDetail }
}
</script>

