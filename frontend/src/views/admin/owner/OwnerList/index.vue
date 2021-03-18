<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="listQuery.title" clearable placeholder="Поиск" style="width: 200px;" class="filter-container__item" @change="handleFilter" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-container__item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-waves class="filter-container__item" type="success" icon="el-icon-plus" @click="addOwner">
        Добавить
      </el-button>
    </div>

    <ShowTable :list="list" :loading="loading" :offset="offset" />
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" @setOffset="setOffset" />
  </div>
</template>

<script>
import { fetchOwnerUserList } from '@/api/admin/owner/owner-api'
import waves from '@/directive/waves' // waves directive
import ShowTable from './components/ShowTable'
import LoadMore from '@/components/LoadMore'

export default {
  components: {
    LoadMore,
    ShowTable
  },
  directives: { waves },
  filters: {
  },
  data() {
    return {
      key: 0,
      list: null,
      offset: 0,
      func: fetchOwnerUserList,
      loading: true,
      listQuery: {
        page: 1,
        limit: 20,
        title: undefined
      },
      dialogFormVisible: false
    }
  },
  computed: {
    mobile() {
      return this.$store.state.app.device === 'mobile'
    }
  },
  mounted() {
  },
  methods: {
    setOffset(val) {
      this.offset = val
    },
    addOwner() {
      this.$router.push('/admin/owner/add')
    },
    setList(val) {
      this.list = val
      this.loading = false
    },
    handleFilter() {
      this.loading = true
      this.listQuery.page = 1
      this.key++
    }
  }
}
</script>

<style scoped>

</style>
