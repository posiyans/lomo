<template>
  <div class="q-pa-md">
    <div class="ma2"><b>Начисления</b></div>
    <div class="filter-container">
      <el-input v-model="listQuery.find" placeholder="Поиск по заголовку" clearable style="width: 200px;" class="filter-container__item" @keyup.enter="handleFilter" />
      <el-select
        v-model="listQuery.type"
        placeholder="Тип"
        class="filter-container__item"
        clearable
        @change="handleFilter"
      >
        <el-option v-for="item in receiptTypes" :key="item.id" :label="item.name" :value="item.id" />
      </el-select>
      <el-button class="filter-container__item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button class="filter-container__item" type="danger" icon="el-icon-plus" @click="add">
        Добавить
      </el-button>
    </div>
    <Desktop :list="list" @reload="getList" />
  </div>
</template>

<script>
import { fetchBillingReestrList } from 'src/Modules/Bookkeeping/api/billingAminApi.js'
import Desktop from './View/Table/Desktop'
import { fetchReceiptTypeList } from 'src/Modules/Receipt/api/receiptAdminApi.js'

export default {
  name: 'ArticleList',
  components: {
    // todo !!! Pagination,
    Desktop
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
  mounted() {
    this.getTypeList()
    this.getList()
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
      this.$router.push('/admin/bookkeping/billing_reestr_create')
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
