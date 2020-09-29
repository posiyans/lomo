<template>
  <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column min-width="160px" label="Заголовок">
      <template slot-scope="{row}">
        <span class="do-not-carry">{{ row.id }}. {{ row.title }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="сотка/участок">
      <template slot-scope="{row}">
        <span>{{ row.ratio_a }}</span>/<span>{{ row.ratio_b}}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Actions">
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
