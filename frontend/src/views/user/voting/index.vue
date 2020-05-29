<template>
  <div>
    <div class="filter-container">
      <el-row :gutter="10" class="el-col">
         <el-col :xs="22" :span="2" :md="6">
           <el-select v-model="listQuery.type" placeholder="Тип голосования" clearable class="filter-item" style="width: 230px">
             <el-option v-for="item in TypeObject" :key="item.key" :label="item.title" :value="item.key" />
           </el-select>
         </el-col>
         <el-col :xs="22" :md="6">
           <el-select v-model="listQuery.status" placeholder="Статус голосования" clearable multiple class="filter-item" style="width: 230px">
             <el-option v-for="item in StatusObject" :key="item.key" :label="item.title" :value="item.key" />
           </el-select>
         </el-col>
         <el-col :xs="22" :md="6">
           <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
             Показать
           </el-button>
         </el-col>
      </el-row>
    </div>
    <div v-loading="listLoading"  v-for="item in list" :key="item.id">
      <VotingPreview :voting="item"/>
    </div>
    <LoadMore v-loading="addLoading" :length="list.length" :total="total" @download="addVoting"/>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="fetchVoting" />

  </div>
</template>

<script>
import { fetchUserVotingList } from '@/api/user/voting'
import VotingPreview from './VotingPreview'
import Pagination from '@/components/Pagination'
import LoadMore from '@/components/LoadMore'
import waves from '@/directive/waves' // waves directive

export default {
  components: {
    VotingPreview,
    Pagination,
    LoadMore
  },
  directives: { waves },
  data() {
    return {
      listLoading: true,
      addLoading: false,
      total: 0,
      list: [],
      TypeObject: [
        {
          key: 'public',
          title: 'Публичное голосование'
        },
        {
          key: 'owner',
          title: 'Голосование собственников'
        }
      ],
      StatusObject: [
        { key: 'new', title: 'Новое' },
        { key: 'execution', title: 'Идет' },
        { key: 'done', title: 'Законченно' },
        { key: 'cancel', title: 'Отмененное' }
      ],
      listQuery: {
        page: 1,
        limit: 10,
        status: ['new', 'execution'],
        sort: '-time'
      }
    }
  },
  computed: {
    loadMore(){

      if (this.total > this.list.length){
        return 'Загрузить еще'
      }
      return false
    }
  },
  mounted() {
    this.fetchVoting()
  },
  methods: {
    handleFilter() {
      this.listQuery.page = 1
      this.fetchVoting()
    },
    add() {
      this.listQuery.page += 1
      if (this.$route.params.id) {
        this.listQuery.category_id = this.$route.params.id
      }

      fetchUserVotingList(this.listQuery).then(response => {
        const data = response.data.data
        data.forEach(item => {
          this.list.push(item)
        })
      })
    },
    addVoting() {
      this.addLoading = true
      fetchUserVotingList(this.listQuery).then(response => {
        response.data.data.forEach(item => {
          this.list.push(item)
        })
        this.total = response.data.meta.total
        this.addLoading = false
      })
    },
    fetchVoting() {
      this.listLoading = true
      fetchUserVotingList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.meta.total
        this.listLoading = false
      })
    }
  }
}
</script>

<style scoped>
 .filter-container {
   padding-left: 25px;
   padding-top: 10px;
 }
</style>
