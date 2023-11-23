<template>
  <el-table v-loading="listLoading" :data="list" size="mini" border fit highlight-current-row style="width: 100%">
    <el-table-column label="Участок" align="center" width="125px">
      <template #default="{row}">
        <span>{{ row.number }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Размер, кв.м." align="center" width="120px">
      <template #default="{row}">
        <span>{{ row.size }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Баланс" width="100px">
      <template #default="{row}">
        <span :class="balansFilter(row.balans_all)">{{ row.balans_all }}</span>
      </template>
    </el-table-column>
    <el-table-column v-for="item in type" :key="item.id" align="center" :label="item.name" width="100px">
      <template #default="{row}">
        <span :class="balansFilter(row.balans['d' + item.id])">{{ row.balans['d' + item.id] }}</span>
      </template>
    </el-table-column>
    <el-table-column align="center" width="250px" label="Последний платеж">
      <template #default="{row}">
        <span v-if="row.last_payment" class="f7">
          {{ row.last_payment.payment_date }}<br>
          <i>{{ row.last_payment.raw_data[4] }}</i><br>
          {{ row.last_payment.price }} руб.
        </span>
      </template>
    </el-table-column>
    <el-table-column align="center" label="Actions" width="180px">
      <template #default="scope">
        <router-link :to="'/admin/bookkeeping/billing_balance_stead/'+scope.row.id">
          <!--          <el-button type="primary" size="mini" icon="el-icon-info">-->
          <div class="more-button">
            Подробнее

          </div>
          <!--          </el-button>-->
        </router-link>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: {
    type: {
      type: Array,
      default: () => []
    },
    list: {
      type: Array,
      default: () => []
    },
    listLoading: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    balansFilter(val) {
      if (val < 0) {
        return 'text-red'
      }
      if (val > 0) {
        return 'text-green'
      }
    }
  }
}
</script>

<style scoped>
.more-button {
  display: inline-block;
  border: 1px solid #676767;
  padding: 0 5px;
  border-radius: 5px;
  cursor: pointer;
  color: #0a0062;
}

.more-button:hover {
  background-color: #d9d9d9;
  border: 1px solid #000;
}
</style>
