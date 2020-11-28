<template>
  <div>
    <div v-if="loadMore" v-loading="listLoading" style="width: 100%; margin-top: 5px;"><el-button type="info" plain style="width: 100%;" @click="add">{{ loadMore }}</el-button></div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'
export default {
  components: {
    Pagination
  },
  props: {
    listQuery: {
      type: Object,
      default: () => {}
    },
    func: {
      type: Function,
      default: () => {}
    }
  },
  data() {
    return {
      total: 0,
      offset: 0,
      list: [],
      showCount: 0,
      listLoading: false
    }
  },
  computed: {
    loadMore() {
      if (this.total > this.showCount) {
        return 'Загрузить еще'
      }
      return false
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      this.func(this.listQuery)
        .then(response => {
          if (response.data.total) {
            this.total = response.data.total
          }
          if (response.data.meta.total) {
            this.total = response.data.meta.total
          }
          if (response.data.offset) {
            this.offset = response.data.offset
          }
          // if (response.data.status) {
          this.list = response.data.data
          // }

          this.listLoading = false
          this.showCount = this.listQuery.page * this.listQuery.limit
          this.$emit('setList', this.list)
          this.$emit('setOffset', this.offset)
        })
    },
    add() {
      this.listQuery.page += 1
      this.listLoading = true
      this.func(this.listQuery)
        .then(response => {
          if (response.data.total) {
            this.total = response.data.total
          }
          if (response.data.meta.total) {
            this.total = response.data.meta.total
          }
          // this.list = []
          setTimeout(() => {
            this.listLoading = false
            response.data.data.forEach(val => {
              this.list.push(val)
              this.showCount++
            })
          }, 500)
          this.$emit('setList', this.list)
        })
    }
  }
}
</script>

<style scoped>
</style>
