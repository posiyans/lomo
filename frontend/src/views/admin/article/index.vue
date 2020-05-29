<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.find" placeholder="Поиск" clearable style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <!--      <el-select v-model="listQuery.importance" placeholder="Imp" clearable style="width: 90px" class="filter-item">-->
      <!--        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />-->
      <!--      </el-select>-->
      <el-select v-model="listQuery.category" placeholder="Раздел" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in categoryArray" :key="item.id" :label="item.label" :value="item.id" />
      </el-select>
      <el-select v-model="listQuery.status" placeholder="Статус" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in statusArray" :key="item.key" :label="item.value" :value="item.key" />
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
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
          <span>{{ scope.row.publish_time | moment('HH:mm DD-MM-YYYY') }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="!mobile"  class-name="status-col" label="Статус" width="120">
        <template slot-scope="{row}">
          <el-tag :type="row.public | statusFilter">
            {{ row.public | publicFilter }}
          </el-tag>
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
            <span>{{ categoryTitle(row.category_id) }}</span>
        </template>
      </el-table-column>
      <el-table-column  v-if="!mobile" class-name="status-col" label="Комментарии" width="120">
        <template slot-scope="{row}">
          <el-tag :type="row.allow_comments | statusFilter">
            {{ row.comments.length }}
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
import { fetchCategoryList } from '@/api/category'
import waves from '@/directive/waves'
import {mapState} from "vuex"; // waves directive

export default {
  name: 'ArticleList',
  components: { Pagination },
  directives: { waves },
  filters: {
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
      categoryArray: [],
      statusArray: [
        {
          key: 0,
          value: 'Черновик'
        },
        {
          key: 1,
          value: 'Опубликовано'
        }
      ],
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
    this.getCategoryList()
  },
  methods: {
    categoryTitle(id) {
      let label = ''
      this.categoryArray.forEach(i => {
        if (i.id === id) {
          label = i.label
        }
      })
      return label
    },
    getCategoryList() {
      const listQuery = {
        children: false
      }
      fetchCategoryList(listQuery).then(response => {
        this.categoryArray = response.data
      })
    },
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
