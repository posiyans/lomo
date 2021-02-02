<template>
  <el-table v-loading="listLoading" :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column label="Участок" align="center" width="125px">
      <template slot-scope="{row}">
        <span>{{ row.number }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Размер, кв.м." align="center" width="120px">
      <template slot-scope="{row}">
        <span>{{ row.size }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Баланс" width="100px">
      <template slot-scope="{row}">
        <span :class="row.balans_all | balansFilter">{{ row.balans_all | formatPrice }}</span>
      </template>
    </el-table-column>
    <el-table-column v-for="item in type" :key="item.id" align="center" :label="item.name" width="100px">
      <template slot-scope="{row}">
        <span :class="row.balans['d' + item.id] | balansFilter">{{ row.balans['d' + item.id] | formatPrice }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" width="150px" label="Последний платеж">
      <template slot-scope="{row}">
        <span v-if="row.last_payment">
          {{ row.last_payment.payment_date }}<br>
          <i>{{ row.last_payment.discription }}</i><br>
          {{ row.last_payment.price }} руб.
        </span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Actions" width="180px">
      <template slot-scope="scope">
        <router-link :to="'/bookkeping/billing_balance_stead/'+scope.row.id">
          <el-button type="primary" size="small" icon="el-icon-info">
            Подробнее
          </el-button>
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
    type: {
      type: Array,
      default: () => { [] }
    },
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

<style scoped>

</style>
