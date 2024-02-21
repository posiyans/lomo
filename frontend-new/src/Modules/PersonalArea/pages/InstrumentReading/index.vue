<template>
  <div>
    <q-card>
      <q-card-section>
        <div v-if="multiStead">
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
              <ForStead :stead-id="stead.id" />
            </q-tab-panel>
          </q-tab-panels>
        </div>
        <div v-else>
          <div class="q-pl-sm text-primary">
            Участок: {{ currentStead.number }}
          </div>
          <ForStead v-if="tab" :stead-id="tab" />
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { getMySteads } from 'src/Modules/Stead/api/stead'
import ForStead from './ForStead.vue'

export default defineComponent({
  components: {
    ForStead
  },
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const steads = ref([])
    const tab = ref(null)
    const multiStead = computed(() => {
      return steads.value.length > 1
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
    onMounted(() => {
      getData()
    })
    return {
      data,
      steads,
      multiStead,
      tab,
      currentStead
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

