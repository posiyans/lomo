<template>
  <div class="app-container">
    <div class="row">
      <div>
        <el-input v-model="listQuery.find" placeholder="Поиск" clearable style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      </div>
      <ArticleCategorySelect v-model="listQuery.category" @input="handleFilter" />
      <StatusSelect v-model="listQuery.status" placeholder="Статус" clearable @input="handleFilter" />
      <div>
        <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
          Показать
        </el-button>
      </div>
    </div>

    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="{ row }">
          <el-tag :type="row.public | statusFilter">
            {{ row.id }}
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column v-if="!mobile" width="150px" align="center" label="Дата">
        <template slot-scope="scope">
          <ShowPublicTime :time="scope.row.updated_at" />
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" class-name="status-col" label="Статус" width="120">
        <template slot-scope="{row}">
          <StatusShow :value="row.public" color />
        </template>
      </el-table-column>

      <el-table-column min-width="300px" label="Заголовок">
        <template slot-scope="{row}">
          <router-link :to="'/admin-article/edit/'+row.id" class="link-type">
            <span>{{ row.title }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" label="Раздел">
        <template slot-scope="{row}">
          <CategoryShow :value="row.category_id" />
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile" class-name="status-col" label="Комментарии" width="120">
        <template slot-scope="{row}">
          <el-tag :type="row.allow_comments | statusFilter">
            row.comments.length
          </el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="120">
        <template slot-scope="scope">
          <router-link :to="'/admin-article/edit/'+scope.row.id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              Edit
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import { fetchAdminArticleList } from '@/api/article'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import waves from '@/directive/waves'
import { mapState } from 'vuex' // waves directive
import StatusShow from '@/Modules/Article/Article/components/StatusShow'
import StatusSelect from '@/Modules/Article/Article/components/StatusSelect'
import ArticleCategorySelect from '@/Modules/Article/Category/components/ArticleCategorySelect'
import CategoryShow from '@/Modules/Article/Category/components/CategoryShow'
import ShowPublicTime from '@/Modules/Article/Article/components/ShowPublicTime'

export default {
  name: 'ArticleList',
  components: { Pagination, StatusShow, StatusSelect, ArticleCategorySelect, CategoryShow, ShowPublicTime },
  directives: { waves },
  filters: {
    categoryFilter(val) {
      if (val) {
        return val
      }
      return 'Укажете категорию'
    },
    statusFilter(status) {
      return status ? 'success' : 'info'
    },

    publicFilter(status) {
      return status === 1 ? 'Опубликовано' : 'Черновик'
    },
    commentFilter(status) {
      return status === 1 ? 'Разрешены' : 'Отключены'
    }
  },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        find: null,
        status: null,
        category: null
      }
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    }),
    mobile() {
      if (this.device === 'mobile') {
        return true
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
      fetchAdminArticleList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.meta.total
        this.listLoading = false
      })
    },
    handleFilter() {
      this.getList()
    }
  }
}
</script>

<style scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
</style>
