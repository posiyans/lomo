<template>
  <div class="q-pa-md">
    <div>
      <AddRateGroupBtn />
    </div>

    <q-tabs
      v-model="tab"
      align="left"
      class="text-teal"
      :breakpoint="0"
    >
      <q-tab v-for="item in rateGroup" :key="item.id" :name="'rate_' + item.id" :label="item.name" />
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel v-for="item in rateGroup" :key="item.id" :name="'rate_' + item.id" class="q-pa-xs">
        <EditRateGroup :rate-group="item" />
        <RateGroupEditInfo :rate-group="item" />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import AddRateGroupBtn from 'src/Modules/Rate/components/AddRateGroupBtn/index.vue'
import { getRateGroupList } from 'src/Modules/Rate/api/rateAdminApi'
import RateGroupEditInfo from 'src/Modules/Rate/components/RateGroupEditInfo/index.vue'
import EditRateGroup from 'src/Modules/Rate/components/EditRateGroup/index.vue'

export default defineComponent({
  components: {
    AddRateGroupBtn,
    RateGroupEditInfo,
    EditRateGroup
  },
  props: {},
  setup(props, { emit }) {
    const tab = ref('')
    const rateGroup = ref([])
    const getData = () => {
      getRateGroupList()
        .then(res => {
          rateGroup.value = res.data.data
          if (!tab.value && rateGroup.value.length > 0) {
            tab.value = 'rate_' + rateGroup.value[0].id
          }
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      tab,
      rateGroup
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
