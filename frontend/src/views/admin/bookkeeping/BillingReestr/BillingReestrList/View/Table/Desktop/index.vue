<template>
  <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column align="center" label="ID" width="80">
      <template slot-scope="{ row }">
        <el-tag :type="row.public | statusFilter">
          {{ row.id }}
        </el-tag>
      </template>
    </el-table-column>
    <el-table-column min-width="300px" label="Заголовок">
      <template slot-scope="{row}">
        <span>{{ row.title }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="На 1 сотку">
      <template slot-scope="{row}">
        <span>{{ row.ratio_a }}</span>
      </template>
    </el-table-column>
    <el-table-column  align="center" label="На 1 Участок">
      <template slot-scope="{row}">
        <span>{{ row.ratio_b}}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Actions" width="120">
      <template slot-scope="scope">
        <router-link :to="'/bookkeping/billing_reestr_edit/'+scope.row.id">
          <el-button type="primary" size="small" icon="el-icon-edit">
            Edit
          </el-button>
        </router-link>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  filters: {
    categoryFilter(val) {
      if (val) {
        return val
      }
      return 'Укажете категорию'
    },
    statusFilter(status) {
      return status ? 'success' : 'info'
    },

    publicFilter(status) {
      return status === 1 ? 'Опубликовано' : 'Черновик'
    },
    commentFilter(status) {
      return status === 1 ? 'Разрешены' : 'Отключены'
    }
  },
  props: {
    list: {}
  }
}
</script>

<style scoped>

</style>
