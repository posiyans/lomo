<template>
  <div>
    <q-tabs
      v-model="tab"
      align="left"
      class="text-teal"
      :breakpoint="0"
    >
      <q-tab name="readings" label="Показания" />
      <q-tab name="device" label="Приборы" />
    </q-tabs>
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="device" class="ba b--dark-green" style="min-height: 250px;">
        <MeteringDeviceForStead :stead-id="steadId" />
      </q-tab-panel>
      <q-tab-panel name="readings" class="ba b--dark-green relative-position" style="min-height: 250px;">
        <AddInstrumentReading :stead-id="steadId" @success="reloadReading" class="absolute-top-right" style="top: 15px; right: 18px;" />
        <InstrumentReadingList :key="keyReading" :edit="readingEdit" :stead-id="steadId" @success="reloadReading" />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { getSteadInfo } from 'src/Modules/Stead/api/stead'
import { usePrimaryStore } from 'stores/parimary-store'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import InstrumentReadingList from 'src/Modules/MeteringDevice/components/InstrumentReadingList/index.vue'
import AddInstrumentReading from 'src/Modules/MeteringDevice/components/AddInstrumentReading/index.vue'
import MeteringDeviceForStead from 'src/Modules/MeteringDevice/components/MeteringDeviceForStead/index.vue'

export default defineComponent({
  components: {
    InstrumentReadingList,
    AddInstrumentReading,
    MeteringDeviceForStead
  },
  props: {
    steadId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const tab = ref('readings')
    const keyReading = ref(1)
    const stead = ref({})
    const route = useRoute()
    const primaryStore = usePrimaryStore()
    const authStore = useAuthStore()
    const readingEdit = computed(() => {
      return authStore.checkPermission(['instrument_reading-edit'])
    })
    const getData = () => {
      const data = {
        id: props.steadId,
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
    const reloadReading = () => {
      console.log('reload')
      keyReading.value++
    }
    return {
      tab,
      keyReading,
      readingEdit,
      reloadReading
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
