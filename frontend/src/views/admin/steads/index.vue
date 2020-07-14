<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="Title" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
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
      <el-table-column v-if="device === 'desktop'" label="Собственник"  align="center"min-width="150px">
        <template slot-scope="{row}">
          <span :class="row | fioStyleFilter">{{ row | fioFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="device === 'desktop'" label="Телефон"  align="center" width="150px">
        <template slot-scope="{row}">
          <span>{{ row.user.phone }}</span>
        </template>
      </el-table-column>
      <el-table-column  v-if="device === 'desktop'" label="Email"  align="center" width="150px">
        <template slot-scope="{row}">
          <span>{{ row.user.email }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Actions" align="center" width="270px" class-name="small-padding fixed-width">
        <template slot-scope="{ row }">
          <el-button type="success" size="small" @click="AddNoteShow(row)">
            Прим.
          </el-button>
          <el-button type="primary" size="small" @click="handleUpdate(row)">
            Инфо.
          </el-button>
          <el-button type="primary" size="small" title="Квитанции" @click="getReceipt(row)">
            Квит.
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog :title="dialogStatus" :visible.sync="dialogFormVisible" width="95%" >
      <el-form ref="dataForm" :rules="rules" :model="temp" :label-position="labelPosition" label-width="150px" style="">
        <el-form-item label="Номер участка" prop="number">
          <el-input v-model="temp.number" :readonly="!editor" />
        </el-form-item>
        <el-form-item label="Площадь, км.м" prop="size">
          <el-input v-model="temp.size" :readonly="!editor" />
        </el-form-item>
        <el-form-item label="Владелец">
          <span :class="temp | fioStyleFilter">{{ temp | fioFilter }}</span>
        </el-form-item>

        <el-form-item label="Телефон">
          <span>{{ temp.user.phone }}</span>
        </el-form-item>
        <el-form-item label="Email">
          <span>{{ temp.user.email }}</span>
        </el-form-item>
        <el-form-item label="Примечание">
          <el-input v-model="temp.discriptions.note" :autosize="{ minRows: 2}" type="textarea" placeholder="Место для заметок"  :readonly="!editor" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          Закрыть
        </el-button>
        <el-button v-if="editor" type="primary" @click="updateData">
          Сохранить
        </el-button>
      </div>
    </el-dialog>
    <el-dialog title="Добавить примечание" :visible.sync="dialogNoteVisible" width="80%" >
      <el-form :label-position="labelPosition" label-width="150px">
        <el-form-item label="Примечание">
          <el-input v-model="newNote" :autosize="{ minRows: 2}" type="textarea" placeholder="Место для заметок" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogNoteVisible = false">
          Закрыть
        </el-button>
        <el-button type="primary" @click="addNote">
          Добавить
        </el-button>
      </div>
    </el-dialog>
    <el-dialog title="Скачать квитанцию" :visible.sync="dialogReceipShow" width="80%" >
      {{receiptData.discriptions.fio}}
      <el-form :label-position="labelPosition" label-width="150px">
        <el-form-item label="Оплата">
          <el-select
            v-model="receiptType"
            placeholder="Select"
            @change="getRate()"
          >
            <el-option
              v-for="item in options"
              :key="item.key"
              :label="item.label"
              :value="item.key"
            >
            </el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <template  v-for="i in rate">
        <div>
          {{i.name}} - {{i.rate.discription}}
          <span v-if="i.rate.ratio_a > 0">
             * {{receiptData.size/100}} = {{(receiptData.size/100 * i.rate.ratio_a).toFixed(2)}} руб.
          </span>
        </div>
      </template>
      <div style="margin-top: 10px"><b>Итого: </b>{{receiptData.size | totalFilter(rate)}} руб.</div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogReceipShow = false">
          Закрыть
        </el-button>
        <el-button type="primary" @click="addNote">
          Скачать
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchSteadList, updateStead } from '@/api/stead'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination'
import { mapState } from "vuex"
import { fetchList } from '@/api/admin/rate'

export default {
  name: 'AdminSteadList',
  components: { Pagination },
  directives: { waves },
  filters: {
    totalFilter(size, rate) {
      let sum = 0
      console.log(rate)
      rate.forEach(i => {
        sum = Number(sum) + Number(i.rate.ratio_a) * Number(size) / 100 + Number(i.rate.ratio_b)
      })
      return sum
    },
    fioFilter(row) {
      if (row.user_fullName) {
        return row.user_fullName
      }
      if (row.discriptions) {
        return row.discriptions.fio
      }
      return ''
    },
    fioStyleFilter(row) {
      if (row.user_fullName) {
        return ''
      }
      return 'grey'
    },
  },
  data() {
    return {
      options: [
        { label: 'Коммунальные', key: 1 },
        { label: 'Взносы', key: 2 }
      ],
      receiptType: 2,
      rate: [],
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        title: undefined
      },
      temp: {
        user: {},
        discriptions: {},
        number: '',
        size: ''
      },
      dialogFormVisible: false,
      dialogNoteVisible: false,
      dialogReceipShow: false,
      receiptData: {},
      newNote: '',
      dialogStatus: '',

      rules: {
        number: [{ required: true, message: 'Обязательное поле', trigger: 'change' }],
        size: [{ required: true, message: 'Обязательное поле', trigger: 'change' }],
      },
      downloadLoading: false
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device,
    }),
    labelPosition() {
      return this.device === 'desktop' ? 'left' : 'top'
    },
    editor() {
      if (this.$store.getters.user.allPermissions.includes('edit-stead')) {
        return true
      }
      return false
    },
  },
  mounted() {
    this.getList()
  },
  methods: {
    getRate() {
      console.log('getrate')
      fetchList({ type: this.receiptType }).then(response => {
        this.rate = response.data.data
        this.loading = false
      })
    },
    getReceipt(row) {
      this.getRate()
      this.receiptData = row
      this.dialogReceipShow = true
      console.log(row.id)
      this.$message({
        message: row.id
      })
    },
    getList() {
      this.listLoading = true
      fetchSteadList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.meta.total
        this.listLoading = false
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    // handleModifyStatus(row, status) {
    //   this.$message({
    //     message: '操作Success',
    //     type: 'success'
    //   })
    //   row.status = status
    // },
    // sortChange(data) {
    //   const { prop, order } = data
    //   if (prop === 'created_at') {
    //     this.sortByID(order)
    //   }
    // },
    // sortByID(order) {
    //   if (order === 'ascending') {
    //     this.listQuery.sort = '+created_at'
    //   } else if(order === 'descending') {
    //     this.listQuery.sort = '-created_at'
    //   } else {
    //     this.listQuery.sort = ''
    //   }
    //   this.handleFilter()
    // },
    // resetTemp() {
    //   this.temp = {
    //     id: undefined,
    //     user: {},
    //     importance: 1,
    //     remark: '',
    //     timestamp: new Date(),
    //     title: '',
    //     status: 'published',
    //     type: '',
    //
    //   }
    // },
    AddNoteShow(row) {
      this.note_id = row.id
      this.dialogNoteVisible = true
    },
    addNote() {
      const data = {
        type: 'addNote',
        note: this.newNote
      }
      updateStead(this.note_id, data)
        .then(response => {
          if (response.data.status) {
            this.getList()
            this.$message({
              message: 'Примечание сохранено',
              type: 'success'
            })
          } else {
            this.$message({
              message: 'Ошибка при сохранении',
              type: 'warning'
            })
          }
          this.dialogNoteVisible = false
        })
    },

    handleUpdate(row) {
      this.temp = row // copy obj
      this.dialogStatus = 'Участок № ' + this.temp.number
      this.dialogFormVisible = true
    },
    // handleShow(row) {
    //   this.temp = Object.assign({}, row) // copy obj
    //   this.temp.timestamp = new Date(this.temp.timestamp)
    //   this.dialogStatus = 'show'
    //   this.dialogFormVisible = true
    //   this.$nextTick(() => {
    //     this.$refs['dataForm'].clearValidate()
    //   })
    // },
    updateData() {
      if (this.editor) {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            const data = {
              type: 'update',
              model: this.temp
            }
            updateStead(this.temp.id, data)
              .then(response => {
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
      }
    },
    // handleDelete(row, index) {
    //   this.$notify({
    //     title: 'Success',
    //     message: 'Delete Successfully',
    //     type: 'success',
    //     duration: 2000
    //   })
    //   this.list.splice(index, 1)
    // },
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
