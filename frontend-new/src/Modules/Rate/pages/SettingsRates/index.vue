<template>
  <div class="q-pa-md">
    <ShowRateList class="relative-position" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import AddRateGroupBtn from 'src/Modules/Rate/components/AddRateGroupBtn/index.vue'
import EditRateGroup from 'src/Modules/Rate/components/EditRateGroup/index.vue'
import ShowRateForGroup from 'src/Modules/Rate/components/ShowRateForGroup/index.vue'
import ShowRateList from 'src/Modules/Rate/components/ShowRateList/index.vue'

export default defineComponent({
  components: {
    ShowRateList,
    AddRateGroupBtn,
    EditRateGroup,
    ShowRateForGroup
  },
  props: {},
  setup(props, { emit }) {
    return {}
  }
})
</script>

<style scoped lang='scss'>

</style>
