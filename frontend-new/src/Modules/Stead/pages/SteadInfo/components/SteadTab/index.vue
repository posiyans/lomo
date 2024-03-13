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
      <q-tab name="owner" label="Владелец" />
      <q-tab name="metering_device" label="Приборы учета" />
      <q-tab name="document" label="Документы" />
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="stead">
        <SteadInfoCard :stead-id="steadId" :edit="editAccess" />
      </q-tab-panel>
      <q-tab-panel name="owner">
        <SteadOwnersCard :stead-id="steadId" :edit="editAccess" />
      </q-tab-panel>
      <q-tab-panel name="metering_device">
        <MeteringDeviceForStead :stead-id="steadId" :edit="editDevice" />
      </q-tab-panel>
      <q-tab-panel name="document">
        <SteadDocuments :stead-id="steadId" :edit="editDevice" />
      </q-tab-panel>
    </q-tab-panels>

  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import SteadInfoCard from 'src/Modules/Stead/components/SteadInfoCard/index.vue'
import SteadOwnersCard from 'src/Modules/Stead/components/SteadOwnersCard/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import MeteringDeviceForStead from 'src/Modules/MeteringDevice/components/MeteringDeviceForStead/index.vue'
import SteadDocuments from 'src/Modules/Stead/components/Documents/SteadDocuments/index.vue'

export default defineComponent({
  components: {
    SteadInfoCard,
    SteadOwnersCard,
    MeteringDeviceForStead,
    SteadDocuments
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    }
  },
  setup(props, { emit }) {
    const authStore = useAuthStore()
    const tab = ref('stead')
    const editDevice = computed(() => {
      return authStore.checkPermission('device-edit')
    })
    const editAccess = computed(() => {
      return authStore.checkPermission('stead-edit')
    })
    return {
      tab,
      editAccess,
      editDevice
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
