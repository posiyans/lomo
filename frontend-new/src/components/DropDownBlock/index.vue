<template>
  <div>
    <div v-if="showMobileBlock" style="width: 250px">
      <q-expansion-item
        :label="labelBtn"
      >
        <q-card>
          <q-card-section class="q-pa-none row justify-center text-center">
            <slot></slot>
          </q-card-section>
        </q-card>
      </q-expansion-item>
    </div>
    <div v-else>
      <slot></slot>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    showLabel: {
      type: String,
      default: 'Показать фильтр'
    },
    hideLabel: {
      type: String,
      default: 'Спрятать фильтр'
    },
    onlyMobile: {
      type: Boolean,
      default: false
    },
    maxWidth: {
      type: Number,
      default: 500
    },
  },
  setup(props, { emit }) {
    const showFilter = ref(false)
    const $q = useQuasar()
    const showMobileBlock = computed(() => {
      return $q.platform.is.mobile || !props.onlyMobile || $q.screen.width < props.maxWidth
    })
    const labelBtn = computed(() => {
      return showFilter ? props.hideLabel : props.showLabel
    })
    return {
      showFilter,
      showMobileBlock,
      labelBtn
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
