<template>
  <div>
    <div v-if="loading" class="text-center q-pa-lg`">
      <q-spinner-facebook
        color="primary"
        size="5em"
      />
    </div>
    <div v-else>

      <q-tabs
        v-model="tab"
        class="bg-grey-1"
        active-color="primary"
        active-bg-color="teal-1"
        indicator-color="primary"
        align="left"
        narrow-indicator
        @update:model-value="setTab"
      >
        <q-tab no-caps v-for="item in typeList" :key="item.id" :label="item.name" :name="item.id" />
      </q-tabs>

      <q-separator />

      <q-tab-panels v-model="tab" animated>
        <q-tab-panel v-for="item in typeList" :key="item.id" :name="item.id">
          <TabPane :type="item.id" />
        </q-tab-panel>
      </q-tab-panels>

    </div>
  </div>
</template>

<script>
import TabPane from 'src/Modules/Rates/components/RateTable/index.vue'
import { getReceiptTypeList } from 'src/Modules/Rates/api/rates.js'

export default {
  components: {
    TabPane
  },
  props: {
    tabDefault: {
      type: [String, Number],
      default: ''
    }
  },
  data() {
    return {
      tab: 1,
      loading: true,
      typeList: []
    }
  },
  created() {
    this.getReceipList()
  },
  mounted() {
    if (this.tabDefault) {
      this.tab = +this.tabDefault
    }
  },
  methods: {
    setTab(val) {
      this.$emit('setTab', val)
      // this.$router.push(`${this.$route.path}?tab=${val}`)
    },
    getReceipList() {
      getReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.typeList = response.data.data
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style scoped>

</style>
