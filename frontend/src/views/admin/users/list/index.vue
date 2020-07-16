<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="Поиск" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.type" placeholder="Статус" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in userTypeObject" :key="item.key" :label="item.display_name" :value="item.key" />
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column label="№"  align="center" width="80">
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="ФИО" min-width="150px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <span
            class="link-type"
            :class="{
              'grey' : !row.consent_personal
            }"
            :title="row.consent_personal ? '' : 'Нет согласия на обработку персональных данных'"
            @click="handleShow(row)"
          >
            {{ row | fullNameFilter }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Участок(и)" align="center"  width="150px">
        <template slot-scope="{row}">
          <span v-for="(stead, i) of row.steads" class="link-type" >
             <span v-if=" i > 0" >, </span><span>{{ stead.number }}</span>
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Телефон" align="center"  width="150px">
      <template slot-scope="{row}">
        <span lass="link-type" >
           {{ row.phone }}</span>
      </template>
      </el-table-column>
      <el-table-column label="Email" align="center"  width="250px">
        <template slot-scope="{row}">
        <span
          class="link-type"
          :class="{
          'gren' : row.email_verified_at,
          'grey' : !row.email_verified_at
          }"
          :title="row.email_verified_at ? '' : 'Не подтвержден'"
        >
           {{ row.email }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Статус" class-name="status-col" width="100">
        <template slot-scope="{row}">
          <span
            :class="{'gren' : row.steads.length > 0}"
            :title="row.steads | ownerFilter"
          >
            <i class="el-icon-s-home" />
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Actions" align="center" width="230" class-name="small-padding fixed-width">
        <template slot-scope="{ row }">
          <el-button type="primary" size="small" @click="handleUpdate(row)" circle>
            <svg-icon icon-class="edit" />
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="editFio" :visible.sync="dialogFormVisible">
      <UserInfo v-if="dialogFormVisible" v-model="temp"/>
        <div slot="footer" class="dialog-footer">
          <el-button @click="close">
            Закрыть
          </el-button>
        </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList } from '@/api/admin/user.js'
import waves from '@/directive/waves' // waves directive
import Pagination from '@/components/Pagination' // secondary package based on el-pagination
import UserInfo from './../components/UserInfo.vue'

const userTypeObject = [
  { key: 'owner', display_name: 'Собственник'}
]

const userType = userTypeObject.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})


export default {
  name: 'AdminUserList',
  components: {
    Pagination,
    UserInfo
  },
  directives: { waves },
  filters: {
    ownerFilter(val){
      if (val && val.length > 0){
        return 'Собственник'
      }
      return 'Пользователь'
    },
    fullNameFilter(user) {
      let result = ''
      if (user.last_name) {
        result += user.last_name
      }
      if (user.name) {
        result += ' ' + user.name
      }
      if (user.middle_name) {
        result += ' ' + user.middle_name
      }
      return result
      // return user.last_name + ' ' +user.name + ' ' + user.moddle_name
    },
    // statusFilter(status) {
    //   const statusMap = {
    //     close: 'success',
    //     draft: 'info',
    //     open: 'danger'
    //   }
    //   return statusMap[status]
    // },
    // typeFilter(type) {
    //   const temp = type.split('_')
    //   if (temp[1] in appealType){
    //     return appealType[temp[1]]
    //   }
    //   return 'Другое'
    // }
  },
  data() {
    return {
      // steadsList: [],
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        title: undefined,
        type: undefined,
      },
      userTypeObject,
      temp: {},
      dialogFormVisible: false,
    }
  },
  mounted() {
    this.getList()
    // this.fetchSteads()
  },
  computed: {
    editFio() {
      let fio = ''
      if (this.temp.last_name) {
        fio = this.temp.last_name + ' '
      }
      if (this.temp.name) {
        fio += this.temp.name + ' '
      }
      if (this.temp.middle_name) {
        fio += this.temp.middle_name
      }
      return fio
    }
  },
  methods: {
    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.meta.total
        this.listLoading = false
        // Just to simulate the time of the request
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    handleUpdate(row) {
      // this.$router.push('/users/show/' + row.id)
      this.temp = Object.assign({}, row) // copy obj
      this.temp.steads = this.temp.steads.map(i => {return i.id})
      this.dialogFormVisible = true
    },
    close() {
      this.dialogFormVisible = false
      this.getList()
    }
  }
}
</script>

<style scoped>
  .gren {
    color: #42b983
  }
  .grey {
    color: #a9a9a9
  }
</style>
