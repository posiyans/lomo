<template>
  <q-card v-if="steads.length > 0">
    <q-card-section class="q-pa-xs q-pa-sm-sm">
      <div v-if="multiStead">
        <div>
          <q-btn-dropdown
            color="primary"
            flat
            no-wrap
            :disable-dropdown="steads.length === 1"
            no-caps
            :label="'Участок: ' + currentStead.number"
          >
            <q-list>
              <q-item
                clickable
                v-close-popup
                v-for="stead  in steads"
                :key="stead.id"
                @click="setStead(stead)"
              >
                <q-item-section :class="{ 'text-primary': stead.id === currentStead.id }">
                  <q-item-label>Участок {{ stead.number }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </div>
        <q-tab-panels v-model="tab" animated>
          <q-tab-panel
            v-for="stead  in steads"
            :key="stead.id"
            :name="stead.id"
            class="q-pa-none"
          >
            <slot v-bind:stead="stead.id">
            </slot>
          </q-tab-panel>
        </q-tab-panels>
      </div>
      <div v-else>
        <div class="q-pl-sm text-primary">
          Участок: {{ currentStead.number }}
        </div>
        <slot v-bind:stead="tab">
        </slot>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BanUserInfo from 'src/Modules/BanUsers/components/BanUserInfo/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import UserAppealList from 'src/Modules/Appeal/pages/UserApealList/index.vue'
import ShowOwnerInfo from 'src/Modules/Owner/components/ShowOwnerInfo/index.vue'
import { getMySteads } from 'src/Modules/Stead/api/stead'


export default defineComponent({
  components: {
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
    const multiStead = computed(() => {
      return steads.value.length > 0
    })
    const currentStead = computed(() => {
      return steads.value.find(i => i.id === tab.value) || {}
    })

    const getData = () => {
      getMySteads()
        .then(res => {
          steads.value = res.data.data
          tab.value = steads.value[0].id
        })

    }
    const setStead = (stead) => {
      tab.value = stead.id
    }
    onMounted(() => {
      getData()
    })
    return {
      tab,
      isOwner,
      setStead,
      steads,
      authStore,
      multiStead,
      currentStead
    }
  }
})
</script>

<style scoped>

</style>
