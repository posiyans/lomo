<template>
  <div class="app-container">
    <h1>{{ voting.title }}</h1>
    <div class="filter-container">
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-plus" @click="handleFilter">
        Проголосовать
      </el-button>
    </div>

    <el-tabs type="border-card">
      <el-tab-pane label="Результат">
        <el-tag :type="voting.status | statusColorFilter">
          {{ voting.status | statusFilter }}
        </el-tag>
        <div v-for="(question, i) in voting.questions" class="question">
          <div style="padding-bottom: 5px; color: black; font-weight: 600;">{{ i+1 }}.
            <span> {{ question.text }}</span>
          </div>
          <div style="padding-bottom: 5px;">Проголосовало {{ (100*question.answersCount/voting.steadsCount).toFixed(2) }}%  ({{ question.answersCount }} участков)</div>
          <div v-for="(answer, j) in question.answers">
            <div class="answer">

              <div class="an" :style="answer | resultBackgroundFilter(question)">
                {{ j+1 }}.<span>{{ answer.text }} </span> <span>{{ answer.userAnswersCount }} </span><span> ({{ answer | resultFilter(question) }}%)</span>
              </div>
            </div>
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Участки">
        <SteadOwnwer :questions="voting.questions" />
      </el-tab-pane>
      <el-tab-pane label="Описание">
        <div class="question" v-html="voting.description" />
        <div v-if="voting.files.length > 0" class="question">
          <div class="file-list-header">Файлы:</div>
          <ul>
            <li v-for="file in voting.files">{{ file.name }}
              <span class="file-size">{{ file.size | sizeFilter }}</span>
              <el-link :href="file.id | urlFilter " type="success">Скачать</el-link>
            </li>
          </ul>
        </div>

      </el-tab-pane>
    </el-tabs>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="100px" style="width: 600px; margin-left:100px;">
        <el-form-item label="Автор" prop="user">
          <el-tag> {{ temp.user.fullName }} </el-tag> <el-tag type="info">{{ temp.created_at | parseTime(' {d}-{m}-{y} {h}:{i}') }}</el-tag>
        </el-form-item>
        <el-form-item label="Заголовок" prop="title">
          <el-input v-model="temp.title" readonly />
        </el-form-item>
        <el-form-item label="Текст">
          <el-input v-model="temp.text" :autosize="{ minRows: 2}" type="textarea" placeholder="Нет теста =(" readonly />
          <div v-for="message in temp.message" :key="message.id" class="text item">
            <el-avatar :size="30" :src="message.user.avatar"@error="errorHandler">
              <img src="/images/default-avatar.jpg">
            </el-avatar>
            {{ message.user.last_name }} {{ message.user.name }}. {{ message.user.middle_name }}. {{ message.text }}
          </div>
        </el-form-item>

        <el-form-item label="Ответить">
          <el-input v-model="temp.new_message" :autosize="{ minRows: 2, maxRows: 4}" type="textarea" placeholder="Введите сообщение для пользователя" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          Отмена
        </el-button>
        <el-button type="primary" @click="updateData()">
          Отправить
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
import { fetchList, fetchPv, createArticle, updateArticle, updateAppel } from '@/api/admin/appeal'
import { fetchVoting, fetchVotingResuiltList } from '@/api/admin/voting'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination
import SteadOwnwer from './result/SteadOwner.vue' // secondary package based on el-pagination

const calendarTypeOptions = [
  { key: 'CN', display_name: 'China' },
  { key: 'US', display_name: 'USA' },
  { key: 'JP', display_name: 'Japan' },
  { key: 'EU', display_name: 'Eurozone' }
]

const appealTypeObject = [
  { key: 'other', display_name: 'Прочее' },
  { key: 'stead', display_name: 'Участок' }
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

const selectStatusOptions = [
  { key: 'new', display_name: 'Новое' },
  { key: 'execution', display_name: 'Идет' },
  { key: 'done', display_name: 'Законченно' },
  { key: 'cancel', display_name: 'Отмененное' }
]

const Status = selectStatusOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})
export default {
  name: 'AdminVotingResult',
  components: {
    Pagination,
    SteadOwnwer
  },
  directives: { waves },
  filters: {
    statusColorFilter(status) {
      const color = {
        new: 'info',
        execution: '',
        done: 'success',
        cancel: 'danger'
      }
      return color[status]
    },
    urlFilter(val) {
      return process.env.VUE_APP_BASE_API + '/api/user/storage/file/' + val
    },
    resultFilter(answer, question) {
      const count = question.answers.reduce((sum, item) => { return sum + item.userAnswersCount }, 0)
      if (count === 0) {
        return 0
      }
      return (100 * answer.userAnswersCount / count).toFixed(2)
      // return answer.userAnswersCount/count
    },
    resultBackgroundFilter(answer, question) {
      const count = question.answers.reduce((sum, item) => { return sum + item.userAnswersCount }, 0)
      return 'width:' + 600 * answer.userAnswersCount / count + 'px;'
      // return answer.userAnswersCount/count
    },
    statusFilter(status) {
      return Status[status]
    },
    typeFilter(type) {
      const temp = type.split('_')
      if (temp[1] in appealType) {
        return appealType[temp[1]]
      }
      return 'Другое'
    }
  },
  data() {
    return {
      tabActive: 1,
      id: this.$route.params && this.$route.params.id,
      voting: {
        files: []
      },
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
        new_message: '',
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
    this.getVoting()
  },
  methods: {
    getVoting() {
      this.listLoading = true
      fetchVoting(this.id, this.listQuery).then(response => {
        this.voting = response.data.data
        // this.total = response.data.meta.total
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getVoting()
    },
    handleModifyStatus(row, status) {
      row.status = status
      const data = {
        'appeal': row
      }
      updateAppel(data, row.id)
        .then(response => {
          this.$message({
            message: 'Success',
            type: 'success'
          })
        })
    },
    sortChange(data) {
      const { prop, order } = data
      if (prop === 'created_at') {
        this.sortByID(order)
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+created_at'
      } else if (order === 'descending') {
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
        type: ''

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
      const data = {
        'appeal': this.temp
      }
      updateAppel(data, this.temp.id)
        .then(response => {
          const index = this.list.findIndex(v => v.id === this.temp.id)
          // todo поменть на модель польлзователя
          this.temp.message.push({ text: this.temp.new_message, user: { name: 'я' }})
          this.temp.new_message = ''
          this.list.splice(index, 1, this.temp)
          this.dialogFormVisible = false
          this.$notify({
            title: 'Success',
            message: 'Update Successfully',
            type: 'success',
            duration: 2000
          })
        })
        // this.$refs['dataForm'].validate((valid) => {
        //   if (valid) {
        //     const tempData = Object.assign({}, this.temp)
        //     tempData.timestamp = +new Date(tempData.timestamp) // change Thu Nov 30 2017 16:41:05 GMT+0800 (CST) to 1512031311464
        //     updateArticle(tempData).then(() => {
        //       const index = this.list.findIndex(v => v.id === this.temp.id)
        //       this.list.splice(index, 1, this.temp)
        //       this.dialogFormVisible = false
        //       this.$notify({
        //         title: 'Success',
        //         message: 'Update Successfully',
        //         type: 'success',
        //         duration: 2000
        //       })
        //     })
        //   }
        // })
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
    }
    // getSortClass: function(key) {
    //   const sort = this.listQuery.sort
    //   return sort === `+${key}` ? 'ascending' : 'descending'
    // }
  }
}
</script>

<style scoped>
  .file-size {
    padding-left: 10px;
    color: #999;
  }
  .file-list-header {
    font-weight: bold;
    color: #1f2d3d;
  }
  .question{
    margin-top: 10px;
    /*background-color: #cca0ff;*/
    background: rgb(13,0,236);
    background: linear-gradient(315deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);
    padding: 20px;
    width: 640px;
    border-radius: 10px;
  }
  .answer {
    width: 600px;
    background-color: #86468f;
    background: rgb(13,0,236);
    background: linear-gradient(135deg, rgba(13,0,236,1) 0%, rgba(0,112,196,1) 0%, rgba(175,217,249,1) 100%);
    color: white;
    /*padding: 5px;*/
    border-radius: 5px;
    margin-bottom: 2px;
  }
  .an{
    background-color: #0070c4;
    /*width: 100px;*/
    height: 100%;
    /*word-wrap: break-word;*/
    white-space:nowrap;
    padding: 10px;
    /*display: inline;*/
    border-radius: 5px 0 0 5px;
  }
</style>
