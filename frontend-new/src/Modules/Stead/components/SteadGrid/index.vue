<template>
  <div class="row">
    <SteadBlock
      v-for="stead in SteadsList"
      :key="stead.id"
      :stead="stead"
      :color="colorStead[stead.id] || 'grey'"
      :tooltip="tooltips[stead.id] || ''"
      :after="tooltips[stead.id] || ''"
      :loading="loading"
      @click="steadClick(stead.id)"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getSteadsList } from 'src/Modules/Stead/api/stead'
import SteadBlock from './components/SteadBlock/index.vue'

export default defineComponent({
  components: {
    SteadBlock
  },
  props: {
    colorStead: {
      type: Object,
      default: () => {
        return {}
      }
    },
    tooltips: {
      type: Object,
      default: () => {
        return {}
      }
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const steadClick = (steadId) => {
      console.log('CL1')
      console.log(steadId)
      emit('steadClick', steadId)
    }
    const data = ref(null)
    const SteadsList = ref([])
    const getData = () => {
      getSteadsList()
        .then(response => {
          SteadsList.value = response.data.data
        })
    }
    onMounted(() => {
      getData()
    })

    return {
      data,
      SteadsList,
      steadClick
    }
  }
})
</script>

<style scoped>

</style>
