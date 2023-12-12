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
          <q-tab name="appeal" label="Платежи" />
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
import { defineComponent, ref } from 'vue'
import SteadTab from './components/SteadTab/index.vue'
import { useRoute } from 'vue-router'

export default defineComponent({
  components: {
    SteadTab
  },
  props: {},
  setup(props, { emit }) {
    const tab = ref('steadinfo')
    const route = useRoute()
    const steadId = ref(route.params.id)
    return {
      tab,
      steadId
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
