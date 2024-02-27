<template>
  <div>
    <div v-if="showMobileBlock">
      <q-expansion-item
        v-model="showFilter"
        :label="labelBtn"
        style="width: 300px"
        class="q-pb-xs"
      >
      </q-expansion-item>
      <q-slide-transition
        class="q-pb-xs"
      >
        <div v-if="showFilter">
          <slot></slot>
        </div>
      </q-slide-transition>
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
      return showFilter.value ? props.hideLabel : props.showLabel
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
.show-btn:hover {
  background-color: #f5f7fa;
}

.rotate-180 {
  animation: rotate-180;
  animation-duration: 0.3s;
  animation-timing-function: linear;
  animation-fill-mode: forwards;
}

@keyframes rotate-180 {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(180deg);
  }
}

.rotate-0 {
  animation: rotate-0;
  animation-duration: 0.3s;
  animation-timing-function: linear;
  animation-fill-mode: forwards;
}

@keyframes rotate-0 {
  from {
    transform: rotate(180deg);
  }
  to {
    transform: rotate(0deg);
  }
}
</style>
