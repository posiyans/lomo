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
    id: {
      type: [String, Number, Boolean],
      default: false
    },
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
    console.log(this.id)
    this.getList()
  },
  methods: {
    getList() {
      console.log('adapter')
      console.log(this.id)
      if (this.id) {
        this.getListId()
      } else {
        return this.getListNoId()
      }
    },
    getListId() {
      this.listLoading = true
      console.log(this.id, this.listQuery)
      this.func(this.id, this.listQuery)
        .then(response => {
          this.total = 0
          if (response.data.total) {
            this.total = response.data.total
          } else if (response.data.meta && response.data.meta.total) {
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
        }, error => {
          console.log(error)
        })
    },
    getListNoId() {
      this.listLoading = true
      this.func(this.listQuery)
        .then(response => {
          this.total = 0
          if (response.data.total) {
            this.total = response.data.total
          } else if (response.data.meta && response.data.meta.total) {
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
        }, error => {
          console.log(error)
        })
    },
    add() {
      this.listQuery.page += 1
      this.listLoading = true
      if (this.id) {
        this.func(this.id, this.listQuery)
          .then(response => {
            this.total = 0
            if (response.data.total) {
              this.total = response.data.total
            } else if (response.data.meta && response.data.meta.total) {
              this.total = response.data.meta.total
            }
            setTimeout(() => {
              this.listLoading = false
              response.data.data.forEach(val => {
                this.list.push(val)
                this.showCount++
              })
              this.$emit('setList', this.list)
            }, 500)
          })
      } else {
        this.func(this.listQuery)
          .then(response => {
            this.total = 0
            if (response.data.total) {
              this.total = response.data.total
            } else if (response.data.meta && response.data.meta.total) {
              this.total = response.data.meta.total
            }
            // this.list = []
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
  }
}
</script>

<style scoped>
</style>
