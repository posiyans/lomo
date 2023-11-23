<template>
  <div>
    <div class="page-title">Голосования</div>
    <div class="row items-center q-col-gutter-sm q-mb-sm">
      <div style="min-width: 220px;">
        <SelectTypeVoting v-model="listQuery.type" outlined dense />
      </div>
      <div style="min-width: 220px;">
        <SelectStatusVoting v-model="listQuery.status" outlined dense multiple use-chips />
      </div>
      <div>
        <q-btn no-wrap no-caps color="primary" icon="search" label="Показать" @click="handleFilter" />
      </div>
    </div>
    <div v-for="item in list" :key="item.id">
      <VotingPreview :voting="item" />
    </div>
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
import { fetchUserVotingList } from 'src/Modules/Voting/api/voting.js'
import VotingPreview from 'src/Modules/Voting/components/VotingPreview/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import SelectTypeVoting from 'src/Modules/Voting/components/SelectTypeVoting/index.vue'
import SelectStatusVoting from 'src/Modules/Voting/components/SelectStatusVoting/index.vue'

export default {
  components: {
    SelectTypeVoting,
    VotingPreview,
    LoadMore,
    SelectStatusVoting
  },
  data() {
    return {
      key: 1,
      list: [],
      func: fetchUserVotingList,
      listQuery: {
        page: 1,
        limit: 10,
        status: [],
        sort: '-time'
      }
    }
  },
  methods: {
    setList(val) {
      this.list = val
    },
    handleFilter() {
      this.listQuery.page = 1
      this.key++
    }
  }
}
</script>

<style scoped>

</style>
