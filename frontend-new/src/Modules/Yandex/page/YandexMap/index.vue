<template>
  <div>
    <YandexMap :stead-id="steadId" style="width: 100%; height: 80vh;" />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import YandexMap from 'src/Modules/Yandex/components/YandexMap/index.vue'
import { useRoute } from 'vue-router'

export default defineComponent({
  components: {
    YandexMap
  },
  props: {},
  setup(props, { emit }) {
    const route = useRoute()
    const steadId = computed(() => {
      return route.query.stead || ''
    })
    return {
      steadId
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
