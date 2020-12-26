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
          <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
            Показать
          </el-button>
        </el-col>
      </el-row>
    </div>
    <div v-for="item in list" :key="item.id" v-loading="listLoading">
      <VotingPreview :voting="item" />
    </div>
    <LoadMore :key="'l'+ key" :list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
import { fetchUserVotingList } from '@/api/user/voting'
import VotingPreview from './VotingPreview'
import LoadMore from '@/components/LoadMore'

export default {
  components: {
    VotingPreview,
    LoadMore
  },
  data() {
    return {
      key: 1,
      listLoading: true,
      list: [],
      func: fetchUserVotingList,
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
  methods: {
    setList(val) {
      this.list = val
      this.listLoading = false
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
 .filter-container {
   padding-left: 25px;
   padding-top: 10px;
 }
</style>
