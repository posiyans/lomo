<template>
  <div class="app-container">
    <div class="ma2"><b>Баланс</b></div>
    <div class="filter-container">
      <el-input v-model="listQuery.find" placeholder="Поиск по номеру участка" clearable style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.category" placeholder="Долги" clearable class="filter-item" style="width: 130px" @change="changeCategory">
        <el-option v-for="item in categoryArray" :key="item.key" :label="item.value" :value="item.key" />
      </el-select>
      <el-select v-if="listQuery.category" v-model="listQuery.receipt_type" placeholder="по" clearable class="filter-item" style="width: 130px" @change="changeCategory">
        <el-option v-for="item in receiptTypes" :key="item.id" :label="item.name" :value="item.id" />
      </el-select>
      <el-select v-model="listQuery.payment" placeholder="Последний платеж" clearable class="filter-item" style="width: 200px" @change="changePayment">
        <el-option v-for="item in statusArray" :key="item.key" :label="item.value" :value="item.key" />
      </el-select>
      <el-input v-if="listQuery.payment" v-model="listQuery.month" type="number" placeholder="n месяцев" clearable style="width: 90px;" class="filter-item" @keyup.enter.native="changePayment" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-waves class="filter-item" type="danger" icon="el-icon-plus" @click="addReestr">
        Добавить выписку
      </el-button>
    </div>
    <component :is="componentName" :list="list" :list-loading="listLoading" :type="receiptTypes" />
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
import { fetchBillingBalansList } from '@/api/admin/billing'
import waves from '@/directive/waves'
import LoadMore from '@/components/LoadMore'
import Mobile from './View/Table/Mobile'
import Desktop from './View/Table/Desktop'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'

export default {
  name: 'ArticleList',
  components: { LoadMore, Mobile, Desktop },
  directives: { waves },
  filters: {
    balansFilter(val) {
      if (val < 0) {
        return 'text-red'
      }
      if (val > 0) {
        return 'text-green'
      }
    }
  },
  data() {
    return {
      list: null,
      total: 0,
      func: fetchBillingBalansList,
      categoryArray: [
        {
          key: 1,
          value: 'Без долгов'
        },
        {
          key: 2,
          value: 'С долгами'
        }
      ],
      statusArray: [
        {
          key: 1,
          value: 'Последняя оплата < n месяцев назад'
        },
        {
          key: 2,
          value: 'Последняя оплата > n месяца назад'
        }
      ],
      listLoading: true,
      key: 0,
      listQuery: {
        receipt_type: '',
        page: 1,
        limit: 50,
        find: null,
        payment: null,
        category: null,
        month: 1
      },
      receiptTypes: []
    }
  },
  computed: {
    mobile() {
      if (this.$store.state.app.device === 'mobile') {
        return true
      }
      return false
    },
    componentName() {
      if (this.mobile) {
        return Mobile
      }
      return Desktop
    }
  },
  mounted() {
    this.handleFilter()
    this.getTypeList()
  },
  methods: {
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receiptTypes = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    setList(val) {
      this.list = val
      this.listLoading = false
    },
    // add() {
    //   this.listQuery.page += 1
    //   fetchBillingBalansList(this.listQuery).then(response => {
    //     if (response.data.data) {
    //       const data = response.data.data
    //       data.forEach(item => {
    //         this.list.push(item)
    //       })
    //     }
    //   })
    // },
    addReestr() {
      this.$router.push('/bookkeping/billing_bank_reestr_upload')
    },
    // categoryTitle(id) {
    //   let label = false
    //   this.categoryArray.forEach(i => {
    //     if (i.id === id) {
    //       label = i.label
    //     }
    //   })
    //   return label
    // },
    // getCategoryList() {
    //   const listQuery = {
    //     children: false
    //   }
    //   fetchCategoryList(listQuery).then(response => {
    //     this.categoryArray = response.data
    //   })
    // },
    // getList() {
    //   this.key++
    // },
    changePayment() {
      this.listQuery.page = 1
      this.listQuery.find = null
      this.key++
      this.listLoading = true
    },
    changeCategory() {
      this.listQuery.page = 1
      this.listQuery.find = null
      this.key++

      this.listLoading = true
    },
    handleFilter() {
      this.listQuery.page = 1
      this.key++
      this.listLoading = true
    }
  }
}
</script>

<style scoped>
  /*.edit-input {*/
  /*  padding-right: 100px;*/
  /*}*/
  /*.cancel-btn {*/
  /*  position: absolute;*/
  /*  right: 15px;*/
  /*  top: 10px;*/
  /*}*/
</style>
