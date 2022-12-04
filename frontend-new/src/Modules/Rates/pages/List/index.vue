<template>
  <div>
    <div class="text-h6 text-weight-bold q-py-md">Тарифы</div>
    <div :key="key">
      <q-card>
        <q-tabs
            v-model="tab"
            class="bg-grey-1"
            active-color="primary"
            active-bg-color="teal-1"
            indicator-color="primary"
            align="left"
            narrow-indicator
        >
          <q-tab no-caps v-for="item in typeList" :key="item.id" :label="item.name" :name="item.id" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel v-for="item in typeList" :key="item.id" :name="item.id" >
            <TabPane :type="item.id" />
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>
  </div>
</template>

<script>
import TabPane from 'src/Modules/Rates/components/RateTable/index.vue'
import { getReceiptTypeList } from 'src/Modules/Rates/api/rates.js'
export default {
  components: { TabPane },
  data () {
    return {
      tab: 1,
      key: 1,
      typeList: [],
      activeName: 1
    }
  },
  watch: {
    activeName (val) {
      this.$router.push(`${this.$route.path}?tab=${val}`)
    }
  },
  created () {
    this.getReceipList()
  },
  mounted () {
    // init the default selected tab
    const tab = this.$route.query.tab
    if (tab) {
      this.activeName = +tab
    }
  },
  methods: {
    getReceipList () {
      getReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.typeList = response.data.data
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
          this.key++
        })
    }
  }
}
</script>

<style scoped>

</style>
