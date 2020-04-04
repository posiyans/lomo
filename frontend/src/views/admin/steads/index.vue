<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="Title" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.importance" placeholder="Imp" clearable style="width: 90px" class="filter-item">
        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />
      </el-select>
      <el-select v-model="listQuery.type" placeholder="Type" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in appealTypeObject" :key="item.key" :label="item.display_name" :value="item.key" />
      </el-select>
      <el-select v-model="listQuery.status" placeholder="Статус" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in selectStatusOptions" :key="item.key" :label="item.display_name" :value="item.key" />
      </el-select>
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
      <el-table-column label="№"  align="center" width="80px">
        <template slot-scope="{row}">
          <span>{{ row.number }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Размер, кв.м"  align="center" width="80px">
      <template slot-scope="{row}">
        <span>{{ row.size }}</span>
      </template>
    </el-table-column>
      <el-table-column label="Собственник"  align="center"min-width="150px">
        <template slot-scope="{row}">
          <span :class="row | fioStyleFilter">{{ row | fioFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Телефон"  align="center" width="150px">
        <template slot-scope="{row}">
          <span>{{ row.user.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Email"  align="center" width="150px">
        <template slot-scope="{row}">
          <span>{{ row.user.email }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Actions" align="center" width="230" class-name="small-padding fixed-width">
        <template slot-scope="{row,$index}">
          <el-button type="primary" size="small" @click="handleUpdate(row)">
            Эл. энер.
          </el-button>
        </template>
      </el-table-column>
<!--      <el-table-column label="Дата" prop="created_at" width="150px" sortable="custom" align="center">-->
<!--        <template slot-scope="{row}">-->
<!--          <span>{{ row.created_at  | parseTime(' {d}-{m}-{y} {h}:{i}') }}</span>-->
<!--        </template>-->
<!--      </el-table-column>-->
<!--      <el-table-column label="Тип" align="center"  width="150px">-->
<!--        <template slot-scope="{row}">-->
<!--          <span class="link-type">{{ row.type | typeFilter }}</span>-->
<!--        </template>-->
<!--      </el-table-column>-->
<!--      <el-table-column label="Заголовок" min-width="150px">-->
<!--        <template slot-scope="{row}">-->
<!--          <el-tag>{{ row.user.fName }}</el-tag>-->
<!--          <span class="link-type" @click="handleShow(row)">{{ row.title }}</span>-->
<!--        </template>-->
<!--      </el-table-column>-->
<!--      <el-table-column label="Статус" class-name="status-col" width="100">-->
<!--        <template slot-scope="{row}">-->
<!--          <el-tag :type="row.status | statusFilter">-->
<!--            {{ row.status }}-->
<!--          </el-tag>-->
<!--        </template>-->
<!--      </el-table-column>-->
<!--      <el-table-column label="Actions" align="center" width="230" class-name="small-padding fixed-width">-->
<!--        <template slot-scope="{row,$index}">-->
<!--          <el-button type="primary" size="small" @click="handleUpdate(row)">-->
<!--            Edit-->
<!--          </el-button>-->
<!--          <el-button v-if="row.status=='open'" size="small" type="success" @click="handleModifyStatus(row,'published')">-->
<!--            Закрыть-->
<!--          </el-button>-->
<!--          <el-button v-if="row.status!='open'" size="small" @click="handleModifyStatus(row,'draft')">-->
<!--           Закрыто-->
<!--          </el-button>-->
<!--        </template>-->
<!--      </el-table-column>-->
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 600px; margin-left:100px;">
        <el-form-item label="Автор" prop="user">
          <el-tag> {{ temp.user.fullName }} </el-tag>
        </el-form-item>
        <el-form-item label="Заголовок" prop="title">
          <el-input v-model="temp.title" readonly />
        </el-form-item>
        <el-form-item label="Текст">
          <el-input v-model="temp.text" :autosize="{ minRows: 2}" type="textarea" placeholder="Нет теста =(" readonly />
        </el-form-item>
        <el-form-item label="Дата" prop="timestamp">
          <el-tag>{{ temp.created_at  | parseTime(' {d}-{m}-{y} {h}:{i}') }}</el-tag>
        </el-form-item>
        <el-form-item label="Ответ">
          <el-input v-model="temp.remark" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" placeholder="Please input" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          Cancel
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          Confirm
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
import { fetchList, fetchPv, createArticle, updateArticle } from '@/api/stead'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination

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

const appealTypeObject = [
  { key: 'other', display_name: 'Прочее'},
  { key: 'stead', display_name: 'Участок'},
]

const appealType = appealTypeObject.reduce((acc, cur) => {
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
  components: { Pagination },
  directives: { waves },
  filters: {
    fioFilter(row){
      if (row.user_fullName){
        return row.user_fullName
      }
      return row.discriptions.fio
    },
    fioStyleFilter(row){
      if (row.user_fullName){
        return ''
      }
      return 'grey'
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
      appealTypeObject,
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
  },
  methods: {
    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.meta.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    handleModifyStatus(row, status) {
      this.$message({
        message: '操作Success',
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
      this.temp.timestamp = new Date(this.temp.timestamp)
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    handleShow(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp)
      this.dialogStatus = 'show'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          tempData.timestamp = +new Date(tempData.timestamp) // change Thu Nov 30 2017 16:41:05 GMT+0800 (CST) to 1512031311464
          updateArticle(tempData).then(() => {
            const index = this.list.findIndex(v => v.id === this.temp.id)
            this.list.splice(index, 1, this.temp)
            this.dialogFormVisible = false
            this.$notify({
              title: 'Success',
              message: 'Update Successfully',
              type: 'success',
              duration: 2000
            })
          })
        }
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
  .grey{
    color: #d6d6d6;
  }
</style>
