<template>
  <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%" cell-class-name="balans-table-mobile-cell">
    <el-table-column label="Номер (размер)" align="center">
      <template slot-scope="{row}">
        <span>{{ row.number }}</span> <span style="color: #8f8f8f">({{ row.size }})</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Баланс">
      <template slot-scope="{row}">
        <span :class="row.balans | balansFilter">{{ row.balans }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Взносы/Электр.">
      <template slot-scope="{row}">
        <span :class="row.balans_2 | balansFilter">{{ row.balans_2 }}</span>
        /
        <span :class="row.balans_1 | balansFilter">{{ row.balans_1 }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Actions">
      <template slot-scope="scope">
        <router-link :to="'/bookkeping/billing_balance_stead/'+scope.row.id">
          <el-button type="primary" size="small" icon="el-icon-edit" />
        </router-link>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  filters: {
    balansFilter(val) {
      if (val < 0) {
        return 'text-red'
      }
      if (val > 0) {
        return 'text-green'
      }
    }
  },
  props: {
    list: {
      type: Array,
      default: () => { [] }
    },
    listLoading: {
      type: Boolean,
      default: true
    }
  }
}
</script>

<style>
 .balans-table-mobile-cell .cell {
   padding-left: 0;
   padding-right: 0;
 }
</style>
