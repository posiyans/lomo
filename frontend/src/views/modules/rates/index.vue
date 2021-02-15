<template>
  <div class="app-container">
    <div class="page-title">Тарифы</div>
    <el-tabs v-model="activeName" style="margin-top:15px;" type="border-card">
      <el-tab-pane v-for="item in typeList" :key="item.id" :label="item.name" :name="item.id">
        <keep-alive>
          <tab-pane v-if="activeName==item.id" :type="item.id" />
        </keep-alive>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import tabPane from './components/TabPane'
import { getReceiptTypeList } from '@/api/all/receipt'
export default {
  name: 'Tab',
  components: { tabPane },
  data() {
    return {
      typeList: [],
      activeName: 1
    }
  },
  watch: {
    activeName(val) {
      this.$router.push(`${this.$route.path}?tab=${val}`)
    }
  },
  created() {
    this.getReceipList()
  },
  mounted() {
    // init the default selected tab
    const tab = this.$route.query.tab
    if (tab) {
      this.activeName = +tab
    }
  },
  methods: {
    getReceipList() {
      getReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.typeList = response.data.data
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
    }
  }
}
</script>

<style scoped>

</style>

