<template>
  <div>
    <q-tabs
      v-model="tab"
      align="left"
      class="text-teal"
      :breakpoint="0"
    >

      <q-tab
        v-for="stead  in steads"
        :key="stead.id"
        :name="stead.id"
        :label="stead.number"
      />
    </q-tabs>
    <q-separator color="teal" />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel
        v-for="stead  in steads"
        :key="stead.id"
        :name="stead.id"
        class="q-pa-none"
      >
        <SteadBlock :stead-id="stead.id" />
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import MyProfile from 'src/Modules/Auth/page/MyProfile/index.vue'
import BanUserInfo from 'src/Modules/BanUsers/components/BanUserInfo/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import UserAppealList from 'src/Modules/Appeal/pages/UserApealList/index.vue'
import ShowOwnerInfo from 'src/Modules/Owner/components/ShowOwnerInfo/index.vue'
import { getMySteads } from 'src/Modules/Stead/api/stead'
import SteadBlock from './components/SteadBlock/index.vue'

export default defineComponent({
  components: {
    MyProfile,
    SteadBlock,
    ShowOwnerInfo,
    BanUserInfo,
    UserAppealList
  },
  props: {},
  setup(props, { emit }) {
    const tab = ref(null)
    const steads = ref([])
    const router = useRouter()
    const route = useRoute()
    const authStore = useAuthStore()
    const isOwner = computed(() => {
      return authStore.isOwner
    })

    const getData = () => {
      getMySteads()
        .then(res => {
          steads.value = res.data.data
          tab.value = steads.value[0].id
        })

    }
    onMounted(() => {
      getData()
    })
    return {
      tab,
      isOwner,
      steads,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
