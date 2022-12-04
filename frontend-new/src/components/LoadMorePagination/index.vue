<template>
  <div class="q-py-md">
    <div v-if="loading" class="q-pa-lg text-center">
      <q-spinner-facebook
        color="primary"
        size="4em"
      />
    </div>
    <div v-else>
      <q-btn v-if="loadMore" class="full-width bg-grey-2 text-primary" :loading="listLoading"  :label="loadMore" @click="add" />
      <pagination v-show="total > 0" :total="total" v-model:page="currentPage" v-model:limit="pageSize" :autoScroll="autoScroll" @pagination="getList" />
      <div v-if="total === 0" class="q-pa-lg text-center text-h6 text-primary">
         Пусто
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from 'src/components/Pagination/index.vue'
import { defineComponent } from 'vue'

export default defineComponent({
  props: {
    autoScroll: {
      type: Boolean,
      default: true
    },
    listQuery: { type: Object },
    func: { type: Function }
  },
  components: {
    Pagination
  },
  data () {
    return {
      loading: true,
      total: 0,
      offset: 0,
      list: [],
      showCount: 0,
      listLoading: false
    }
  },
  computed: {
    currentPage: {
      get () {
        return this.listQuery.page
      },
      set (val) {
        const tmp = Object.assign({}, this.listQuery)
        tmp.page = val
        this.$emit('update:listQuery', tmp)
      }
    },
    pageSize: {
      get () {
        return this.listQuery.limit
      },
      set (val) {
        const tmp = Object.assign({}, this.listQuery)
        tmp.limit = val
        this.$emit('update:listQuery', tmp)
      }
    },
    loadMore () {
      if (this.total > this.showCount) {
        return 'Загрузить еще'
      }
      return false
    }
  },
  mounted () {
    this.getList()
  },
  methods: {
    getList () {
      this.listLoading = true
      this.func(this.listQuery)
        .then(response => {
          this.list = response.data.data
          this.total = response.data.meta.total || 0
          this.$emit('setList', this.list)
          this.listLoading = false
          this.showCount = this.listQuery.page * this.listQuery.limit
          this.loading = false
        })
    },
    add () {
      // this.pageSize = this.pageSize + 1
      const tmp = Object.assign({}, this.listQuery)
      tmp.page++
      this.currentPage++
      this.listLoading = true
      this.func(tmp)
        .then(response => {
          this.total = response.data.meta.total || 0
          setTimeout(() => {
            this.listLoading = false
            response.data.data.forEach(val => {
              this.list.push(val)
              this.showCount++
            })
            this.$emit('setList', this.list)
          }, 500)
        })
    }
  }
})
</script>

<style scoped>

</style>
