<template>
  <div>
    <q-tabs
      v-model="tab"
      align="left"
      dense
      class="text-teal"
      :breakpoint="0"
    >
      <q-tab name="stead" label="Участок" />
      <q-tab name="ban" label="Владелец" />
      <q-tab name="appeal" label="Приборы учета" />
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="stead">
        <SteadInfoCard :stead-id="steadId" />
      </q-tab-panel>
    </q-tab-panels>

  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import SteadInfoCard from 'src/Modules/Stead/components/SteadInfoCard/index.vue'

export default defineComponent({
  components: {
    SteadInfoCard
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const tab = ref('stead')
    return {
      tab
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
