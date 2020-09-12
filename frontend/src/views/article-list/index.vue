<template>
  <div>
    <div v-if="listLoading"  v-for="item in list" :key="item.id">
      <ArticlePreview :data="item"/>
    </div>
    <div v-if="loadMore" style="width: 100%;"><el-button type="warning" plain @click="add" style="width: 100%;">{{ loadMore }}</el-button></div>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="fetchArticle" />

  </div>
</template>

<script>
import { fetchListForCategory, fetchList } from '@/api/article'
import ArticlePreview from '@/components/ArticlePreview'
import Pagination from '@/components/Pagination'
export default {
  components: {
    ArticlePreview,
    Pagination
  },
  data() {
    return {
      listLoading: false,
      total: 0,
      list: [],
      listQuery: {
        page: 1,
        limit: 10,
        category_id: 1,
        sort: '-time'
      }
    }
  },
  computed: {
    loadMore() {
      if (this.total > this.list.length){
        return 'Загрузить еще'
      }
      return false
    }
  },
  mounted() {
    this.fetchArticle()
  },
  methods:{
    add(){
      this.listQuery.page += 1
      if (this.$route.params.id) {
        this.listQuery.category_id = this.$route.params.id
      }

      fetchListForCategory(this.listQuery).then(response => {
        const data = response.data.data
        data.forEach(item => {
          this.list.push(item)
        })
      })
    },
    fetchArticle() {
      this.listLoading = false
      if (this.$route.params.id) {
        this.listQuery.category_id = this.$route.params.id
      }

      fetchListForCategory(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        this.listLoading = true
      })
    }
  }
}
</script>

<style scoped>

</style>
