<template>
  <div class="pagination-container">
    <div class="flex q-gutter-md">
      <div class="self-center">
        Всего {{ total }}
      </div>
      <div class="self-center page-size-block">
        <q-select
            dense
            v-model="pageSize"
            :options="pageSizes"
            :display-value="`${limit ? limit + ' на странице' : ''}`"
            @input="handleSizeChange"
        >
        </q-select>
      </div>
      <div class="self-center">
        <q-pagination
            v-model="currentPage"
            :max="maxPage"
            :max-pages="6"
            glossy
            dense
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
import { scrollTo } from 'src/utils/scroll-to'

export default {
  name: 'Pagination',
  props: {
    total: {
      required: true,
      type: Number
    },
    page: {
      type: Number,
      default: 1
    },
    limit: {
      type: Number,
      default: 20
    },
    pageSizes: {
      type: Array,
      default () {
        return [5, 10, 20, 30, 50]
      }
    },
    autoScroll: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    maxPage () {
      return Math.ceil(this.total / this.limit)
    },
    currentPage: {
      get () {
        return this.page
      },
      set (val) {
        this.$emit('update:page', val)
      }
    },
    pageSize: {
      get () {
        return this.limit
      },
      set (val) {
        this.$emit('update:limit', val)
      }
    }
  },
  methods: {
    handleSizeChange (val) {
      this.$emit('pagination')
      if (this.autoScroll) {
        scrollTo(0, 800)
      }
    },
    handleCurrentChange (val) {
      this.$emit('pagination')
      if (this.autoScroll) {
        scrollTo(0, 800)
      }
    }
  }
}
</script>

<style scoped lang="scss">
.pagination-container {
  background: #fff;
  padding: 32px 0;
}
.page-size-block {
  @media all and (max-width: 500px) {
    display: none;
  }
}
</style>
