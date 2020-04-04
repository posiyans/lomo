<template>
  <voting-detail :is-edit="true" />
</template>

<script>
import VotingDetail from './components/VotingDetail'

export default {
  name: 'EditVotingForm',
  components: { VotingDetail }
}
</script>

