<template>
  <div v-if="listLoading">
    <ArticlePreview v-for="item in list" :key="item.id" :data="item" />
  </div>
</template>

<script>
import ArticlePreview from '@/components/ArticlePreview'
import { fetchListForCategory } from '@/api/article'
export default {
  components: {
    ArticlePreview
  },
  data() {
    return {
      listLoading: false,
      total: 0,
      list: [],
      listQuery: {
        page: 1,
        limit: 5,
        sort: '-time'
      }
    }
  },
  computed: {
  },
  mounted() {
    this.fetchArticle()
  },
  methods: {
    add() {
      // console.log('ADD!!!')
      this.listQuery.page += 1
      if (this.$route.params.id) {
        this.listQuery.category_id = this.$route.params.id
      }

      fetchListForCategory(this.listQuery).then(response => {
        // console.log('ADD OK')

        const data = response.data.data
        data.forEach(item => {
          this.list.push(item)
        })
      })
    },
    fetchArticle() {
      // console.log('fewtch!!!')
      // console.log(this.listQuery)
      this.listLoading = false
      if (this.$route.params.id) {
        this.listQuery.category_id = this.$route.params.id
      }

      fetchListForCategory(this.listQuery).then(response => {
        // console.log('fewtch OK')
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
