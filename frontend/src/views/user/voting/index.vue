<template>
  <div class="app-container">
    <h1>Голосования</h1>
    <el-tabs v-model="activeName" style="margin-top:15px;" type="border-card">
      <el-tab-pane v-for="item in tabMapOptions" :key="item.key" :label="item.label" :name="item.key">
        <keep-alive>
          <tab-pane v-if="activeName==item.key" :type="item.key" @create="showCreatedTimes" />
        </keep-alive>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
  import tabPane from './components/TabPane'
  export default {
    name: 'UserVotingPanel',
    components: { tabPane },
    data() {
      return {
        tabMapOptions: [
          { label: 'Действующие', key: '1' },
          { label: 'Завершенные', key: '2' }
        ],
        activeName: '1',
        createdTimes: 0
      }
    },
    watch: {
      activeName(val) {
        this.$router.push(`${this.$route.path}?tab=${val}`)
      }
    },
    mounted() {
      // init the default selected tab
      const tab = this.$route.query.tab
      if (tab) {
        this.activeName = tab
      }
    },
  }
</script>

<style scoped>

</style>
