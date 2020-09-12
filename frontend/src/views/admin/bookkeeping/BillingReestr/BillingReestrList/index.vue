<template>
  <div class="app-container">
    <div class="ma2"><b>Начисления</b></div>
    <div class="filter-container">
      <el-input v-model="listQuery.find" placeholder="Поиск по заголовку" clearable style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select
        v-model="listQuery.type"
        placeholder="Тип"
        clearable
        class="filter-item"
        style="width: 160px"
        @change="handleFilter"
      >
        <el-option v-for="item in categoryArray" :key="item.key" :label="item.title" :value="item.key" />
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-waves class="filter-item" type="danger" icon="el-icon-plus" @click="add">
        Добавить
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
      <el-table-column min-width="300px" label="Заголовок">
        <template slot-scope="{row}">
            <span>{{ row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="На 1 сотку">
        <template slot-scope="{row}">
          <span>{{ row.ratio_a }}</span>
        </template>
      </el-table-column>
      <el-table-column  align="center" label="На 1 Участок">
        <template slot-scope="{row}">
          <span>{{ row.ratio_b}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Actions" width="120">
        <template slot-scope="scope">
          <router-link :to="'/bookkeping/billing_reestr_edit/'+scope.row.id">
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
import { fetchBillingReestrList } from '@/api/admin/billing'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import { fetchCategoryList } from '@/api/category'
import waves from '@/directive/waves'
import {mapState} from "vuex"; // waves directive

export default {
  name: 'ArticleList',
  components: { Pagination },
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
      categoryArray: [
        {
          key: 1,
          title: 'Электроэнергия'
        },
        {
          key: 2,
          title: 'Взносы'
        }
      ],
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        find: null,
        type: null
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
    // this.getCategoryList()
  },
  methods: {
    add() {
      this.$router.push('/bookkeping/billing_reestr_create')
    },
    categoryTitle(id) {
      let label = false
      this.categoryArray.forEach(i => {
        if (i.id === id) {
          label = i.label
        }
      })
      return label
    },
    getList() {
      this.listLoading = true
      fetchBillingReestrList(this.listQuery).then(response => {
        this.listLoading = false
        this.list = response.data.data.data
        // this.total = response.data.meta.total
      })
    },
    handleFilter() {
      this.getList()
    }
  }
}
</script>

<style scoped>

</style>
