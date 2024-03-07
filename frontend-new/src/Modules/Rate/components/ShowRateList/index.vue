<template>
  <div>
    <div v-if="editRate" class="row absolute-top-right" style="top: 10px; right: 10px;z-index: 1000;">
      <AddRateGroupBtn>
        <q-btn icon="add" flat dense color="teal" />
      </AddRateGroupBtn>
      <EditRateGroup v-if="activeTabData" :rate-group="activeTabData" @success="getData">
        <q-btn icon="more_horiz" flat dense color="teal" />
      </EditRateGroup>
    </div>
    <q-tabs
      v-model="tab"
      align="left"
      class="text-teal"
      v-loading="loading"
      :breakpoint="0"
      @update:model-value="setTab"
    >
      <q-tab v-for="item in rateGroup" :key="item.id" :name="item.id" :label="item.name" />
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels
      v-model="tab"
      v-loading="loading"
      animated
    >
      <q-tab-panel v-for="item in rateGroup" :key="item.id" :name="item.id" class="q-pa-xs">
        <ShowRateForGroup :rate-group-id="item.id" :edit="editRate" />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import AddRateGroupBtn from 'src/Modules/Rate/components/AddRateGroupBtn/index.vue'
import { getRateGroupList } from 'src/Modules/Rate/api/rateAdminApi'
import EditRateGroup from 'src/Modules/Rate/components/EditRateGroup/index.vue'
import ShowRateForGroup from 'src/Modules/Rate/components/ShowRateForGroup/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'


export default defineComponent({
  components: {
    AddRateGroupBtn,
    EditRateGroup,
    ShowRateForGroup
  },
  props: {
    modelValue: {
      type: [String, Number],
      default: 0
    },
    tabDefault: {
      type: [String, Number],
      default: 0
    }
  },
  setup(props, { emit }) {
    const tab = ref('')
    const loading = ref(true)
    const rateGroup = ref([])
    const authStore = useAuthStore()
    const editRate = computed(() => {
      return authStore.checkPermission(['rate-edit'])
    })
    const activeTabData = computed(() => {
      return rateGroup.value.find(item => item.id === tab.value)
    })
    const getData = () => {
      loading.value = true
      getRateGroupList()
        .then(res => {
          rateGroup.value = res.data.data
          if (props.modelValue > 0) {
            tab.value = props.modelValue
          } else if (!tab.value && rateGroup.value.length > 0) {
            tab.value = rateGroup.value[0].id
          }
          console.log(tab.value)
          setTab(tab.value || '')
        })
        .finally(() => {
          loading.value = false
        })
    }
    const setTab = (val) => {
      emit('update:model-value', val)
    }
    onMounted(() => {
      getData()
    })
    return {
      tab,
      setTab,
      getData,
      editRate,
      loading,
      activeTabData,
      rateGroup
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
