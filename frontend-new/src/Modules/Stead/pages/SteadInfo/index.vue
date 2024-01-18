<template>
  <div class="q-pa-xs">
    <q-card>
      <q-card-section>
        <q-tabs
          v-model="tab"
          align="left"
          class="text-teal"
          :breakpoint="0"
        >
          <q-tab name="steadinfo" label="Данные" />
          <q-tab name="ban" label="Показания" />
          <q-tab name="payment" label="Платежи" />
        </q-tabs>
        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="steadinfo" class="ba b--dark-green" style="min-height: 250px;">
            <SteadTab :stead-id="steadId" />
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import SteadTab from './components/SteadTab/index.vue'
import { useRoute } from 'vue-router'
import { getSteadInfo } from 'src/Modules/Stead/api/stead'
import { usePrimaryStore } from 'stores/parimary-store'

export default defineComponent({
  components: {
    SteadTab
  },
  props: {},
  setup(props, { emit }) {
    const tab = ref('steadinfo')
    const stead = ref({})
    const route = useRoute()
    const steadId = ref(route.params.id)
    const primaryStore = usePrimaryStore()
    const getData = () => {
      const data = {
        id: steadId.value,
        full: 1
      }
      getSteadInfo(data)
        .then(res => {
          stead.value = res.data.data
          primaryStore.setPageName('Участок № ' + stead.value.number)
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      tab,
      steadId
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
