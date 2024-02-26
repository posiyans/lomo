<template>
  <div>
    <AddMeteringDevice v-if="edit" :stead-id="steadId" @close="getData" />
    <div class="text-right">
      <AddInstrumentReading :stead-id="steadId" @success="getData" />
      <ShowTable :list="devices" :edit="edit" @reload="getData" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'
import ShowTable from './components/ShowTable/index.vue'
import AddMeteringDevice from 'src/Modules/MeteringDevice/components/AddMeteringDevice/Btn.vue'
import AddInstrumentReading from 'src/Modules/MeteringDevice/components/AddInstrumentReading/index.vue'

export default defineComponent({
  components: {
    ShowTable,
    AddMeteringDevice,
    AddInstrumentReading
  },
  props: {
    steadId: {
      type: [Number, String],
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const devices = ref([])
    const getData = () => {
      getMeteringDeviceForStead(props.steadId)
        .then(res => {
          devices.value = res.data.data
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      data,
      getData,
      devices
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
