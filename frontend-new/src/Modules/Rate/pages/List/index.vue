<template>
  <div>
    <div class="page-title">
      Тарифы
    </div>
    <q-card>
      <q-card-section>
        <ShowRateList :tab-default="tab" @setTab="setTab" />
      </q-card-section>
    </q-card>

  </div>
</template>

<script>
import ShowRateList from 'src/Modules/Rate/components/ShowRateList/index.vue'

export default {
  components: {
    ShowRateList
  },
  data() {
    return {
      tab: 1
    }
  },
  mounted() {
    const tab = this.$route.query.tab
    if (tab) {
      this.tab = +tab
    }
  },
  methods: {
    setTab(val) {
      this.$router.push(`${this.$route.path}?tab=${val}`)
    }
  }
}
</script>

<style scoped>

</style>
