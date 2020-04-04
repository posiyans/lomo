<template>
  <div>
    <div v-if="listLoading"  v-for="item in list" :key="item.id">
      <ArticlePreview :data="item"/>
    </div>
    <div v-if="listLoading" style="width: 100%;"><el-button type="warning" plain @click="add" style="width: 100%;">Загрузить еще</el-button></div>
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
  computed: {
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
  mounted() {
    this.fetchArticle()
  },
  methods:{
    add(){
      console.log('ADD!!!')
      this.listQuery.page += 1
      if (this.$route.params.id) {
        this.listQuery.category_id = this.$route.params.id
      }

      fetchListForCategory(this.listQuery).then(response => {
      console.log('ADD OK')

        const data = response.data.data
        data.forEach(item => {
          this.list.push(item)
        })
      })
    },
    fetchArticle(){
      console.log('fewtch!!!')
      console.log(this.listQuery)
      this.listLoading = false
      if (this.$route.params.id) {
        this.listQuery.category_id = this.$route.params.id
      }

      fetchListForCategory(this.listQuery).then(response => {
        console.log('fewtch OK')
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
