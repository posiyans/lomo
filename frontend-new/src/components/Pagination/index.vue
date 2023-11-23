<template>
  <div class="pagination-container">
    <div class="row q-gutter-sm items-center no-wrap">
      <div>
        Всего {{ total }}
      </div>
      <div class="page-size-block">
        <q-select
          dense
          :model-value="limit"
          :options="pageSizes"
          :display-value="`${limit ? limit + ' на странице' : ''}`"
          @update:model-value="handleSizeChange"
        >
        </q-select>
      </div>
      <div>
        <q-pagination
          :model-value="+page"
          :max="max"
          :max-pages="maxPage"
          glossy
          dense
          :input="mobile"
          direction-links
          icon-prev="fast_rewind"
          icon-next="fast_forward"
          @update:model-value="handleCurrentChange"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    total: {
      required: true,
      type: [Number, String]
    },
    page: {
      type: [Number, String],
      default: 1
    },
    limit: {
      type: [Number, String],
      default: 20
    },
    pageSizes: {
      type: Array,
      default() {
        return [5, 10, 20, 30, 50]
      }
    }
  },
  setup(props, { emit }) {
    const $q = useQuasar()
    const mobile = computed(() => {
      return $q.screen.width < 800
    })
    const maxPage = computed(() => {
      return Math.floor(($q.screen.width - 150) / 90)
    })
    const max = computed(() => {
      return Math.ceil(props.total / props.limit)
    })
    const data = ref(false)
    const handleSizeChange = (val) => {
      emit('update:limit', val)
    }
    const handleCurrentChange = (val) => {
      emit('update:page', val)
    }
    return {
      data,
      handleSizeChange,
      handleCurrentChange,
      max,
      maxPage,
      mobile
    }
  }
})
</script>

<style scoped lang="scss">
.pagination-container {
  padding: 24px 0;
}

.page-size-block {
  @media all and (max-width: 500px) {
    display: none;
  }
}
</style>
