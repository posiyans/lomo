<template>
  <div>
    <div class="page-title">Погода</div>
    <div>
      <q-card>
        <q-card-section>
          <q-tabs
            v-model="tab"
            active-color="primary"
            indicator-color="primary"
            align="left"
            narrow-indicator
            @update:model-value="changeTab"
          >
            <q-tab name="table" label="Погода" />
            <q-tab name="chart" label="График" />
          </q-tabs>
          <q-separator />
          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="table">
              <TableData />
            </q-tab-panel>
            <q-tab-panel name="chart">
              <ChartData />
            </q-tab-panel>
          </q-tab-panels>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import { defineComponent, onMounted, ref } from 'vue'
import TableData from 'src/Modules/Weather/componets/WeatherTable/index.vue'
import ChartData from 'src/Modules/Weather/componets/WeatherChart/index.vue'
import { useRoute, useRouter } from 'vue-router/dist/vue-router'

export default defineComponent({
  components: {
    ChartData,
    TableData
  },
  setup(props, { emit }) {
    const route = useRoute()
    const tab = ref(route.query.tab ?? 'table')
    const router = useRouter()
    const changeTab = (val) => {
      router.replace({ path: route.fullPath, query: { tab: val } })
    }
    onMounted(() => {

    })
    return {
      tab,
      changeTab
    }
  }
})
</script>

<style scoped>

</style>
