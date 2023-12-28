<template>
  <div class="q-pa-md">
    <div class="page-title">Тарифы</div>
    <q-card>
      <q-card-section>
        <ShowRateList />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import AddRateGroupBtn from 'src/Modules/Rate/components/AddRateGroupBtn/index.vue'
import { getRateGroupList } from 'src/Modules/Rate/api/rateAdminApi'
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
    const tab = ref('')
    const rateGroup = ref([])
    const activeTabData = computed(() => {
      return rateGroup.value.find(item => item.id === tab.value)
    })
    const getData = () => {
      getRateGroupList()
        .then(res => {
          rateGroup.value = res.data.data
          if (!tab.value && rateGroup.value.length > 0) {
            tab.value = rateGroup.value[0].id
          }
        })
    }
    onMounted(() => {
      getData()
    })
    return {
      tab,
      getData,
      activeTabData,
      rateGroup
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
