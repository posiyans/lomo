<template>
  <div class="app-container">
    <h2>Погода</h2>

    <el-tabs v-model="activeName" style="margin-top:15px;" type="border-card">
      <el-tab-pane v-for="item in tabMapOptions" :key="item.key" :label="item.label" :name="item.key">
        <keep-alive>
          <div v-if="activeName == item.key">
            <component  v-bind:is="show(item.key)"></component>
          </div>
        </keep-alive>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import New from './components/New'
import Old from './components/Old'

export default {
  name: 'Tab',
  components: { New, Old },
  data() {
    return {
      tabMapOptions: [
        { label: 'Будет', key: '1' },
        { label: 'Было', key: '2' }
      ],
      activeName: '1',
      createdTimes: 0
    }
  },
  computed: {
    componentName() {
      if (this.activeName === '2') {
        return ''
      }
      if (this.activeName === '1') {
        return New
      }
      return false
    }
  },
  methods: {
    show(key) {
      if (key == '2') {
        return Old
      }
      if (key == '1') {
        return New
      }
      return New
    }
  },
  watch: {
    activeName(val) {
      // console.log('watch')
      this.$router.push(`${this.$route.path}?tab=${val}`)
    }
  },
  mounted() {
    // init the default selected tab
    // console.log('mounted index')
    const tab = this.$route.query.tab
    if (tab) {
      this.activeName = tab
    }
  },
}
</script>

<style scoped>
  .tab-container {
    margin: 30px;
  }
</style>

