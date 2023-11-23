<template>
  <div class="q-pa-md">
    <div class="filter-container">
      <el-input v-model="listQuery.title" clearable placeholder="Поиск" style="width: 200px;" class="filter-container__item" @change="handleFilter" @keyup.enter="handleFilter" />
      <el-button class="filter-container__item" type="primary" icon="el-icon-search" @click="handleFilter">
        Показать
      </el-button>
      <el-button v-if="editable" class="filter-container__item" type="success" icon="el-icon-plus" @click="addOwner">
        Добавить
      </el-button>
      <el-button class="filter-container__item" type="success" icon="el-icon-download" @click="download">
        XLSX
      </el-button>
    </div>

    <ShowTable :list="list" :loading="loading" :offset="offset" />
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" @setOffset="setOffset" />
  </div>
</template>

<script>
import { fetchOwnerListInXlsx, fetchOwnerUserList } from 'src/Modules/Owner/api/owner-admin-api.js'
import ShowTable from './components/ShowTable'
import LoadMore from 'src/components/LoadMore/index.vue'
import { exportFile } from 'quasar'

export default {
  components: {
    LoadMore,
    ShowTable
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
      return this.$q.platform.is.mobile
    },
    editable() {
      // return this.$store.state.user.roles.indexOf('write-personal-data') !== -1
      return false
    }
  },
  mounted() {
  },
  methods: {
    download() {
      fetchOwnerListInXlsx(this.listQuery).then(response => {
        const blob = new Blob([response.data])
        exportFile('Список.xlsx', blob)
        this.$message('Файл успешно скачан.')
      })
    },
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
