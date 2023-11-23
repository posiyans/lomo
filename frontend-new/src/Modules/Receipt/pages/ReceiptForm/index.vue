<template>
  <div>
    <div class="page-title">
      Реквизиты для оплаты
    </div>
    <q-card>
      <q-card-section>
        <GardeningRequisite />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>

import { defineComponent, onMounted, ref } from 'vue'
import GardeningRequisite from 'src/Modules/Gardening/components/GardeningRequisite/index.vue'

export default defineComponent({
  components: {
    GardeningRequisite
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(null)

    onMounted(() => {

    })
    return {
      data
    }
  }
})
</script>

<style scoped>

</style>
