<template>
  <div class="app-container">
    <h1>{{ value.title }}</h1>
    <div class="filter-container">
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-plus" @click="addUserAnswer">
        Проголосовать
      </el-button>
    </div>
    <el-tabs v-model="tabActive" type="border-card">
      <el-tab-pane label="Результат">
        <div>
          Статус:
          <el-tag :type="voting.status | statusColorFilter">
            {{ voting.status | statusFilter }}
          </el-tag>
        </div>
        <div v-for="(question, i) in questions" :key="question.id">
          <ResultBlock :question="question" :i="i" :steat-count="voting.steadsCount" />
        </div>
      </el-tab-pane>
      <el-tab-pane label="Участки">
        <SteadResult v-if="tabActive == 1" :id="id" :questions="questions" />
      </el-tab-pane>
      <el-tab-pane label="Таблица">
        <SteadTable v-if="tabActive == 2" :questions="questions" />
      </el-tab-pane>
      <el-tab-pane label="Описание">
        <div class="question" v-html="voting.description" />
        <div v-if="voting.files.length > 0" class="question">
          <div class="file-list-header">Файлы:</div>
          <ul>
            <li v-for="file in voting.files" :key="file.id">{{ file.name }}
              <span class="file-size">{{ file.size | sizeFilter }}</span>
              <el-link :href="file.id | urlFilter " type="success">Скачать</el-link>
            </li>
          </ul>
        </div>

      </el-tab-pane>
    </el-tabs>

  </div>
</template>

<script>
import { fetchVotingQuestions } from '@/api/admin/voting'
import waves from '@/directive/waves' // waves directive
// import SteadOwnwer from './SteadOwner.vue' // secondary package based on el-pagination
import SteadResult from './components/SteadResult' // secondary package based on el-pagination
import SteadTable from './components/SteadTable' // secondary package based on el-pagination
import ResultBlock from '@/components/VotingOwnerResulBlock'

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
    SteadResult,
    SteadTable,
    ResultBlock
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
    statusFilter(status) {
      return Status[status]
    }
  },
  props: {
    value: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      tabActive: 0,
      id: this.$route.params && this.$route.params.id,
      voting: {
        files: []
      },
      tableKey: 0,
      list: null,
      total: 0,
      listLoading: true,
      importanceOptions: [1, 2, 3],
      selectStatusOptions,
      questions: [],
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
    this.voting = this.value
    // this.getVoting()
    this.getQuestions()
  },
  methods: {
    addUserAnswer() {
      this.$router.push('/admin/voting/addAnswer/' + this.value.id)
    },
    getQuestions() {
      fetchVotingQuestions(this.voting.id)
        .then(response => {
          this.questions = response.data.data
        })
    }

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

</style>
