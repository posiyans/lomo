<template>
  <div class="app-container">
    <div class="ma2"><b>Баланс</b></div>
    <div class="filter-container">
      <el-input v-model="listQuery.find" placeholder="Поиск по номеру участка" clearable class="filter-container__item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.category" placeholder="Долги" clearable class="filter-container__item" @change="changeCategory">
        <el-option v-for="item in categoryArray" :key="item.key" :label="item.value + ' ('+ listQuery.zeroLine + ')' " :value="item.key" />
      </el-select>
      <el-select v-if="listQuery.category" v-model="listQuery.receipt_type" placeholder="по" clearable class="filter-container__item" style="width: 130px" @change="changeCategory">
        <el-option v-for="item in receiptTypes" :key="item.id" :label="item.name" :value="item.id" />
      </el-select>
      <!--      <el-select v-model="listQuery.payment" placeholder="Последний платеж" clearable class="filter-item" style="width: 200px" @change="changePayment">-->
      <!--        <el-option v-for="item in statusArray" :key="item.key" :label="item.value" :value="item.key" />-->
      <!--      </el-select>-->
      <!--      <el-input v-if="listQuery.payment" v-model="listQuery.month" type="number" placeholder="n месяцев" clearable style="width: 90px;" class="filter-item" @keyup.enter.native="changePayment" />-->
      <el-button v-waves class="filter-container__item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-waves class="filter-container__item" type="primary" icon="el-icon-download" @click="getExcel">
        XLSX
      </el-button>
      <el-button v-waves class="filter-container__item" icon="el-icon-setting" @click="settingShow" />
    </div>
    <component :is="componentName" :list="list" :list-loading="listLoading" :type="receiptTypes" />
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
    <el-dialog
      title="Настройки"
      :visible.sync="dialogSettingVisible"
      :close-on-click-modal="false"
      width="300px"
      size="small"
    >
      <div>
        <el-form ref="settingForm" label-position="top" @submit.prevent.native="">
          <el-form-item label="Долг при балансе меньше">
            <el-input v-model="listQuery.zeroLine" type="number" style="width: 100px;" />
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="settingClose">Ok</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchBillingBalansList, fetchBillingBalansXLSX } from '@/api/admin/billing'
import waves from '@/directive/waves'
import LoadMore from '@/components/LoadMore'
import Mobile from './View/Table/Mobile'
import Desktop from './View/Table/Desktop'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'
import Cookies from 'js-cookie'
import { saveAs } from 'file-saver'

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
      dialogSettingVisible: false,
      setting: {
        zeroLine: 0
      },
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
        type: 'json',
        zeroLine: 0,
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
  created() {
    this.listQuery.zeroLine = Cookies.get('balansListSettingZeroLine') || 0
  },
  mounted() {
    this.handleFilter()
    this.getTypeList()
  },
  methods: {
    getExcel() {
      const data = Object.assign({}, this.listQuery)
      data.type = 'xlsx'
      fetchBillingBalansXLSX(data).then(response => {
        const blob = new Blob([response.data])
        saveAs(blob, 'Список.xlsx')
        this.$message('Файл успешно скачан.')
      })
    },
    settingShow() {
      this.dialogSettingVisible = true
    },
    settingClose() {
      Cookies.set('balansListSettingZeroLine', this.listQuery.zeroLine)
      this.dialogSettingVisible = false
      this.handleFilter()
    },
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
