<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" placeholder="Поиск" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.type" placeholder="Голосование" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in TypeObject" :key="item.key" :label="item.display_name" :value="item.key" />
      </el-select>
      <el-select v-model="listQuery.status" placeholder="Статус" clearable class="filter-item" style="width: 130px">
        <el-option v-for="item in selectStatusOptions" :key="item.key" :label="item.display_name" :value="item.key" />
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
      <el-table-column label="№" align="center" width="80">
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Заголовок" min-width="150px">
        <template slot-scope="{row}">
          <span class="link-type" @click="showResult(row.id)">{{ row.title }}</span>
          <el-button type="success" size="mini" plain icon="el-icon-s-custom">{{ row.countAnswer }}</el-button>
        </template>
      </el-table-column>
      <el-table-column label="Публикация" prop="date_publish" width="150px" align="center">
        <template slot-scope="{row}">
          <span>{{ row.date_publish | parseTime(' {d}-{m}-{y} {h}:{i}') }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="false" label="Дата начала" prop="date_start" width="150px" align="center">
        <template slot-scope="{row}">
          <span v-if="row.type === 'owner'">{{ row.date_start | parseTime('{d}-{m}-{y}') }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="false" label="Дата конца" prop="date_stop" width="150px" align="center">
        <template slot-scope="{row}">
          <span v-if="row.type === 'owner'">{{ row.date_stop | parseTime('{d}-{m}-{y}') }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Тип" align="center" width="150px">
        <template slot-scope="{row}">
          <span class="link-type">{{ row.type | typeFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Статус" class-name="status-col" width="100">
        <template slot-scope="{ row }">
          <el-tag :type="row.status | statusColorFilter">
            {{ row.status | statusFilter }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Actions" align="center" width="320px" class-name="small-padding fixed-width">
        <template slot-scope="{ row }">
          <router-link v-if="row.status === 'new' || row.type === 'public'" :to="'/admin/voting/edit/' + row.id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              Edit
            </el-button>
          </router-link>
          <router-link v-if="(row.status =='execution' || row.status !=='cancel') && row.status !== 'new'" :to="'/admin/voting/result/' + row.id">
            <el-button type="success" size="small" icon="el-icon-edit">
              Результат
            </el-button>
          </router-link>
          <el-button v-if="row.status === 'new' || row.status === 'execution'" size="small" type="danger" @click="handleModifyStatus(row)">
            Отменить
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog title="Закончить голосование" :visible.sync="dialogFormVisible">
      <div>
        Укажите как закончить голосование!!!
      </div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          Отмена
        </el-button>
        <el-button type="danger" class="mx-4" @click="updateStatus('cancel')">
          Отменить
        </el-button>
        <el-button type="success" class="mr-4" @click="updateStatus('done')">
          Закончить голосование
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchVotingList, updateVoting } from '@/api/admin/voting'
import waves from '@/directive/waves' // waves directive
import Pagination from '@/components/Pagination' // secondary package based on el-pagination

const selectStatusOptions = [
  { key: 'new', display_name: 'Новое' },
  { key: 'execution', display_name: 'Идет' },
  { key: 'done', display_name: 'Законченно' },
  { key: 'cancel', display_name: 'Отменено' }
]

const TypeObject = [
  { key: 'public', display_name: 'Публичное' },
  { key: 'owner', display_name: 'Собственников' }
]

const Type = TypeObject.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

const Status = selectStatusOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'AdminVotingListTable',
  components: { Pagination },
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
    statusFilter(status) {
      return Status[status]
    },
    typeFilter(temp) {
      if (temp in Type) {
        return Type[temp]
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
        status: '',
        sort: '+created_at'
      },
      dialogFormVisible: false,
      selectStatusOptions,
      TypeObject,
      temp: {}
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    showResult(id) {
      this.$router.push('/admin/voting/result/' + id)
    },
    getList() {
      this.listLoading = true
      fetchVotingList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.meta.total
        this.listLoading = false
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    updateStatus(status) {
      this.temp.status = status
      // console.log(status)
      // console.log(this.temp)
      const data = {
        'voting': this.temp
      }
      updateVoting(data, this.temp.id)
        .then(response => {
          this.dialogFormVisible = false
          // console.log(response.data)
          this.$message({
            message: 'Success',
            type: 'success'
          })
        })
    },
    handleModifyStatus(row) {
      this.temp = row
      this.dialogFormVisible = true
    }
  }
}
</script>
