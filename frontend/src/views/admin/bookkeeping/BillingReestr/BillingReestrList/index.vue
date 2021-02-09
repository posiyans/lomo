<template>
  <div class="app-container">
    <div class="ma2"><b>Начисления</b></div>
    <div class="filter-container">
      <el-input v-model="listQuery.find" placeholder="Поиск по заголовку" clearable style="width: 200px;" class="filter-container__item" @keyup.enter.native="handleFilter" />
      <el-select
        v-model="listQuery.type"
        placeholder="Тип"
        class="filter-container__item"
        style="width: 160px"
        @change="handleFilter"
      >
        <el-option v-for="item in receiptTypes" :key="item.id" :label="item.name" :value="item.id" />
      </el-select>
      <el-button v-waves class="filter-container__item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-if="roles.indexOf('reestr-edit') != -1" v-waves class="filter-container__item" type="danger" icon="el-icon-plus" @click="add">
        Добавить
      </el-button>
    </div>
    <component :is="componentName" :list="list" @reload="getList" />
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import { fetchBillingReestrList } from '@/api/admin/billing'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination
import waves from '@/directive/waves'
import Mobile from './View/Table/Mobile'
import Desktop from './View/Table/Desktop'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'

export default {
  name: 'ArticleList',
  components: { Pagination, Mobile, Desktop },
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
      receiptTypes: [],
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
    mobile() {
      return this.$store.state.app.device === 'mobile'
    },
    user() {
      return this.$store.state.user
    },
    roles() {
      return this.user.roles
    },
    componentName() {
      if (this.mobile) {
        return Mobile
      }
      return Desktop
    }
  },
  created() {
    this.getTypeList()
  },
  mounted() {
    this.getList()
    // this.getCategoryList()
  },
  methods: {
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receiptTypes = []
            response.data.data.forEach(item => {
              // if (!item.auto_invoice) {
              this.receiptTypes.push(item)
              // }
            })
            if (this.receiptTypes.length === 1) {
              this.listQuery.type = this.receiptTypes[0].id
            }
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
    },
    add() {
      this.$router.push('/bookkeping/billing_reestr_create')
    },
    getList() {
      this.listLoading = true
      fetchBillingReestrList(this.listQuery).then(response => {
        this.listLoading = false
        this.list = response.data.data.data
        this.total = response.data.total
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
