<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="Поиск" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
<!--      <el-select v-model="listQuery.importance" placeholder="Imp" clearable style="width: 90px" class="filter-item">-->
<!--        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />-->
<!--      </el-select>-->
      <el-select v-model="listQuery.type" placeholder="Статус" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in userTypeObject" :key="item.key" :label="item.display_name" :value="item.key" />
      </el-select>
<!--      <el-select v-model="listQuery.status" placeholder="Статус" clearable class="filter-item" style="width: 130px">-->
<!--        <el-option v-for="item in selectStatusOptions" :key="item.key" :label="item.display_name" :value="item.key" />-->
<!--      </el-select>-->
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-if="false" v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        Export
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
      @sort-change="sortChange"
    >
      <el-table-column label="№"  align="center" width="80">
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="ФИО" min-width="150px">
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
          <span
            :class="{'gren' : false}"
            :title="'Член правления' | titleFilter(false)">
            <svg-icon icon-class="user" />
          </span>

          <span
            :class="{'gren' : row.consent_to_email}"
            :title="'Подписан на рассылку' | titleFilter(row.consent_to_email)">
            <svg-icon icon-class="email" />
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Actions" align="center" width="230" class-name="small-padding fixed-width">
        <template slot-scope="{row,$index}">
          <el-button type="primary" size="small" @click="handleUpdate(row)" circle>
            <svg-icon icon-class="edit" />
          </el-button>
          <el-button size="small" type="success" @click="handleModifyStatus(row,'published')" circle>
            <svg-icon icon-class="eye-open" />
          </el-button>
          <el-button v-if="row.status!='open'" size="small" type="warning" @click="handleModifyStatus(row,'draft')" circle>
            <svg-icon icon-class="guide" />
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="editFio" :visible.sync="dialogFormVisible">
        <div v-if="dialogFormVisible" class="article-preview-body" style="padding: 0 10px; max-width: 600px; margin: 0 auto" >
          <AvatarUpload :user_id="temp.id" v-model="temp.avatar"/>
        </div>
        <RoleAndPermision v-model="temp.roles">
          <el-tab-pane label="Данные">
            <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="200px" style="">
              <el-form-item label="E-mail">
                <el-input v-model="temp.email" readonly></el-input>
              </el-form-item>
              <el-form-item label="Фамилия">
                <el-input v-model="temp.last_name"></el-input>
              </el-form-item>
              <el-form-item label="Имя">
                <el-input v-model="temp.name"></el-input>
              </el-form-item>
              <el-form-item label="Отчество">
                <el-input v-model="temp.middle_name"></el-input>
              </el-form-item>
              <el-form-item label="Телефон">
                <el-input v-model="temp.phone"></el-input>
              </el-form-item>
              <el-form-item label="Адрес регистрации/почтовый адрес">
                <el-input type="textarea" v-model="temp.adres"></el-input>
              </el-form-item>
              <el-form-item label="Номер Участка(ов)">
                <el-select
                  v-model="temp.steads"
                  multiple
                  filterable
                  remote
                  reserve-keyword
                  placeholder="Введите номер участка"
                  no-data-text="Данный номер не найден"
                >
                  <el-option
                    v-for="item in steadsList"
                    :key="item.id"
                    :label="item.number"
                    :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-form>
          </el-tab-pane>
        </RoleAndPermision>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            Закрыть
          </el-button>
          <el-button type="primary" @click="updateData()">
            Сохранить
          </el-button>
        </div>
    </el-dialog>

    <el-dialog :visible.sync="dialogPvVisible" title="Reading statistics">
      <el-table :data="pvData" border fit highlight-current-row style="width: 100%">
        <el-table-column prop="key" label="Channel" />
        <el-table-column prop="pv" label="Pv" />
      </el-table>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogPvVisible = false">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, updateUserData } from '@/api/admin/user.js'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination
import RoleAndPermision from './permisions/index.vue'
import AvatarUpload from '@/components/AvatarUpload'
import { getSteadsList } from '@/api/user/stead.js'

const calendarTypeOptions = [
  { key: 'CN', display_name: 'China' },
  { key: 'US', display_name: 'USA' },
  { key: 'JP', display_name: 'Japan' },
  { key: 'EU', display_name: 'Eurozone' }
]

const selectStatusOptions = [
  { key: 'open', display_name: 'Открытые' },
  { key: 'close', display_name: 'Закрытые' },
  { key: 'all', display_name: 'Все' }
]

const userTypeObject = [
  { key: 'owner', display_name: 'Собственник'},
]

const userType = userTypeObject.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

// arr to obj, such as { CN : "China", US : "USA" }
const calendarTypeKeyValue = calendarTypeOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'ComplexTable',
  components: {
    Pagination,
    RoleAndPermision,
    AvatarUpload
  },
  directives: { waves },
  filters: {
    ownerFilter(val){
      if (val && val.length > 0){
        return 'Собственник'
      }
      return 'Пользователь'
    },
    titleFilter(text, val){
      if (val){
        return text
      }
      return ''
    },
    fullNameFilter(user) {
      let result = ''
      if (user.last_name) {
         result += user.last_name
      }
      if (user.name) {
        result += ' '+ user.name
      }
      if (user.middle_name) {
        result += ' ' + user.middle_name
      }
      return result
      // return user.last_name + ' ' +user.name + ' ' + user.moddle_name
    },
    statusFilter(status) {
      const statusMap = {
        close: 'success',
        draft: 'info',
        open: 'danger'
      }
      return statusMap[status]
    },
    typeFilter(type) {
      const temp = type.split('_')
      if (temp[1] in appealType){
        return appealType[temp[1]]
      }
      return 'Другое'
    }
  },
  data() {
    return {
      steadsList: [],
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        importance: undefined,
        title: undefined,
        type: undefined,
        status: 'open',
        sort: '+created_at'
      },
      importanceOptions: [1, 2, 3],
      calendarTypeOptions,
      selectStatusOptions,
      userTypeObject,
      sortOptions: [{ label: 'ID Ascending', key: '+id' }, { label: 'ID Descending', key: '-id' }],
      statusOptions: ['published', 'draft', 'deleted'],
      temp: {
        id: undefined,
        user: {},
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        type: '',
        status: 'published'
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Edit',
        create: 'Create',
        show: 'Обращение'
      },
      // appealType: {
      //   stead: 'Участок'
      // },
      dialogPvVisible: false,
      pvData: [],
      rules: {
        // type: [{ required: true, message: 'type is required', trigger: 'change' }],
        // timestamp: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }],
        // title: [{ required: true, message: 'title is required', trigger: 'blur' }]
      },
      downloadLoading: false
    }
  },
  mounted() {
    this.getList()
    this.fetchSteads()
    console.log('mouunt user')
  },
  computed: {
    editFio() {
      return this.temp.last_name + ' ' + this.temp.name + ' ' + this.temp.middle_name + '' + this.temp.avatar
    },
  },
  methods: {
    fetchSteads(){
      getSteadsList()
        .then(response => {
          console.log(response.data)
          this.steadsList = response.data
        })
    },
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
    handleModifyStatus(row, status) {
      this.$message({
        message: 'Success',
        type: 'success'
      })
      row.status = status
    },
    sortChange(data) {
      const { prop, order } = data
      if (prop === 'created_at') {
        this.sortByID(order)
      }
    },
    sortByID(order) {
      console.log(order)
      if (order === 'ascending') {
        this.listQuery.sort = '+created_at'
      } else if(order === 'descending') {
        this.listQuery.sort = '-created_at'
      } else {
        this.listQuery.sort = ''
      }
      this.handleFilter()
    },
    resetTemp() {
      this.temp = {
        id: undefined,
        user: {},
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        status: 'published',
        type: '',

      }
    },
    // handleCreate() {
    //   this.resetTemp()
    //   this.dialogStatus = 'create'
    //   this.dialogFormVisible = true
    //   this.$nextTick(() => {
    //     this.$refs['dataForm'].clearValidate()
    //   })
    // },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.temp.id = parseInt(Math.random() * 100) + 1024 // mock a id
          this.temp.author = 'vue-element-admin'
          createArticle(this.temp).then(() => {
            this.list.unshift(this.temp)
            this.dialogFormVisible = false
            this.$notify({
              title: 'Success',
              message: 'Created Successfully',
              type: 'success',
              duration: 2000
            })
          })
        }
      })
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.steads = this.temp.steads.map(i => {return i.id})
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    handleShow(row) {
      // this.temp = Object.assign({}, row) // copy obj
      // this.temp.timestamp = new Date(this.temp.timestamp)
      // this.dialogStatus = 'show'
      // this.dialogFormVisible = true
      // this.$nextTick(() => {
      //   this.$refs['dataForm'].clearValidate()
      // })
    },
    updateData() {
      const data = {
        user: this.temp
      }
      updateUserData(data, this.temp.id)
        .then(response => {
          const index = this.list.findIndex(v => v.id === this.temp.id)
          this.list.splice(index, 1, this.temp)
          console.log(response.data)
          this.dialogFormVisible = false
          this.$notify({
            title: 'Успех',
            message: 'Данные успешно сохранены',
            type: 'success',
            duration: 2000
          })
          this.getList()
        })
    },
    handleDelete(row, index) {
      this.$notify({
        title: 'Success',
        message: 'Delete Successfully',
        type: 'success',
        duration: 2000
      })
      this.list.splice(index, 1)
    },
    handleFetchPv(pv) {
      fetchPv(pv).then(response => {
        this.pvData = response.data.pvData
        this.dialogPvVisible = true
      })
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['timestamp', 'title', 'type', 'importance', 'status']
        const filterVal = ['timestamp', 'title', 'type', 'importance', 'status']
        const data = this.formatJson(filterVal)
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'table-list'
        })
        this.downloadLoading = false
      })
    },
    formatJson(filterVal) {
      return this.list.map(v => filterVal.map(j => {
        if (j === 'timestamp') {
          return parseTime(v[j])
        } else {
          return v[j]
        }
      }))
    },
    // getSortClass: function(key) {
    //   const sort = this.listQuery.sort
    //   return sort === `+${key}` ? 'ascending' : 'descending'
    // }
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
