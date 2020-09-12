<template>
  <div class="app-container">
    <div class="ma2"><b>Счета и платежи по участку № {{stead.number}} размер {{stead.size}} кв.м.</b></div>
    <div class="ma2">Валанс:  <b>{{stead.balans}} руб.</b></div>
<!--    <div class="filter-container">-->
<!--      <el-input v-model="listQuery.find" placeholder="Поиск" clearable style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />-->
<!--      &lt;!&ndash;      <el-select v-model="listQuery.importance" placeholder="Imp" clearable style="width: 90px" class="filter-item">&ndash;&gt;-->
<!--      &lt;!&ndash;        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />&ndash;&gt;-->
<!--      &lt;!&ndash;      </el-select>&ndash;&gt;-->
<!--      <el-select v-model="listQuery.category" placeholder="Раздел" clearable class="filter-item" style="width: 130px">-->
<!--        <el-option v-for="item in categoryArray" :key="item.id" :label="item.label" :value="item.id" />-->
<!--      </el-select>-->
<!--      <el-select v-model="listQuery.status" placeholder="Статус" clearable class="filter-item" style="width: 130px">-->
<!--        <el-option v-for="item in statusArray" :key="item.key" :label="item.value" :value="item.key" />-->
<!--      </el-select>-->
<!--      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">-->
<!--        Показать-->
<!--      </el-button>-->
<!--      <el-button v-waves class="filter-item" type="danger" icon="el-icon-plus" @click="add">-->
<!--        Добавить-->
<!--      </el-button>-->
<!--    </div>-->

    <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column label="Дата">
        <template slot-scope="{row}">
          <span>{{ row.created_at | moment('DD-MM-YYYY')}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Название">
        <template slot-scope="{ row }">
            {{ row.title }}
        </template>
      </el-table-column>
      <el-table-column label="Дебет">
        <template slot-scope="{row}">
            <span>{{ row.price}}</span>
        </template>
      </el-table-column>
      <el-table-column label="Кредит">
        <template slot-scope="{row}">
<!--          <span>{{ // row.price}}</span>-->
        </template>
      </el-table-column>
<!--      <el-table-column label="Размер, кв.м.">-->
<!--        <template slot-scope="{row}">-->
<!--          <span>{{ row.size}}</span>-->
<!--        </template>-->
<!--      </el-table-column>-->
      <el-table-column align="center" label="Actions" >
        <template slot-scope="scope">
          <router-link :to="'/bookkeping/billing_balance_stead/'+scope.row.id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              Подробнее
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
import { fetchBillingBalansSteadInfo } from '@/api/admin/billing'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import { fetchCategoryList } from '@/api/category'
import waves from '@/directive/waves'
import {mapState} from "vuex"; // waves directive

export default {
  name: 'ArticleList',
  components: { Pagination },
  directives: { waves },
  filters: {
    balansFilter(val) {
      if (val < 0) {
        return 'text-red'
      }
      if (val > 0) {
        return 'text-green'
      }
    },
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
  props: {
    // id: {
    //   type: Number,
    //   default: 0
    // }
  },
  data() {
    return {
      id: '',
      stead: '',
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
  created() {
    this.id = this.$route.params && this.$route.params.id
    this.getData()
  },
  methods: {
    getData() {
      fetchBillingBalansSteadInfo({stead_id: this.id}).then(response => {
        console.log(response)
        this.stead = response.data.data.stead_info
        this.list = response.data.data.invoices
        this.listLoading = false
      })
    },
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
      fetchBillingNBalansList(this.listQuery).then(response => {
        this.listLoading = false
        this.list = response.data.data
        this.total = response.data.meta.total
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
