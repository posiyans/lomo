<template>
  <div>
    <AddMeteringDevice v-if="edit" :stead-id="steadId" @close="getData" />
    <ShowTable :list="devices" :edit="edit" @reload="getData" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'
import ShowTable from './components/ShowTable/index.vue'
import AddMeteringDevice from 'src/Modules/MeteringDevice/components/AddMeteringDevice/Btn.vue'

export default defineComponent({
  components: {
    ShowTable,
    AddMeteringDevice
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
