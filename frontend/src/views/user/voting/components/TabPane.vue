<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column
      v-loading="loading"
      align="center"
      label="ID"
      width="65"
      element-loading-text="Пожалуйста, дайте мне немного времени！"
    >
      <template slot-scope="scope">
        <span>{{ scope.row.id }}</span>
      </template>
    </el-table-column>
    <el-table-column min-width="300px" label="Title">
      <template slot-scope="{row}">
        <span>{{ row.name }}</span>
        <el-tag>{{ row.discription}}</el-tag>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Readings" width="95">
      <template slot-scope="scope">
        <span>{{ scope.row.pageviews }}</span>
      </template>
    </el-table-column>
<!--    <el-table-column width="180px" align="center" label="Date">-->
<!--      <template slot-scope="scope">-->
<!--        <span>{{ scope.row.timestamp | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>-->
<!--      </template>-->
<!--    </el-table-column>-->

<!--    <el-table-column min-width="300px" label="Title">-->
<!--      <template slot-scope="{row}">-->
<!--        <span>{{ row.title }}</span>-->
<!--        <el-tag>{{ row.type }}</el-tag>-->
<!--      </template>-->
<!--    </el-table-column>-->

<!--    <el-table-column width="110px" align="center" label="Author">-->
<!--      <template slot-scope="scope">-->
<!--        <span>{{ scope.row.author }}</span>-->
<!--      </template>-->
<!--    </el-table-column>-->

<!--    <el-table-column width="120px" label="Importance">-->
<!--      <template slot-scope="scope">-->
<!--        <svg-icon v-for="n in +scope.row.importance" :key="n" icon-class="star" />-->
<!--      </template>-->
<!--    </el-table-column>-->

<!--    <el-table-column align="center" label="Readings" width="95">-->
<!--      <template slot-scope="scope">-->
<!--        <span>{{ scope.row.pageviews }}</span>-->
<!--      </template>-->
<!--    </el-table-column>-->

    <el-table-column class-name="status-col" label="Status" width="140">
      <template slot-scope="{row}">
        <el-tag :type="row.enable | statusFilter">
          {{ row.enable ? 'Действующий' : 'Не действующий' }}
        </el-tag>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import { fetchList } from '@/api/rate'

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger'
      }
      return statusMap[status]
    }
  },
  props: {
    type: {
      type: String,
      default: '1'
    }
  },
  data() {
    return {
      list: null,
      listQuery: {
        page: 1,
        limit: 5,
        type: this.type,
        sort: '+id'
      },
      loading: false
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList() {
      this.loading = true
      this.$emit('create') // for test
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.loading = false
      })
    }
  }
}
</script>

