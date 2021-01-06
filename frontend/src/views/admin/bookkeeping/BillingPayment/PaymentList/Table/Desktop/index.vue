<template>
  <div class="billing-bank-reestr-table">
    <el-table
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
      :row-class-name="tableRowClassName"
    >
      <el-table-column label="№" align="center" width="60px">
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Номер участка" align="center" width="125px">
        <template slot-scope="{row}">
          <el-tag style="cursor: pointer" @click="showStead(row.stead_id)">
            {{ row.stead.number }}
          </el-tag>
          <span v-if="row.stead.number !== row.raw_data[5]">
            ({{ row.raw_data[5] }})
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Время" align="center">
        <template slot-scope="{row}">
          <span>{{ row.payment_date | moment("DD-MM-YYYY HH:mm") }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Назначение" align="center">
        <template slot-scope="{row}">
          <span class="do-not-carry">{{ row.discription }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Сумма" align="center">
        <template slot-scope="{row}">
          <span>{{ row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Тип" align="center">
        <template slot-scope="{row}">
          <span>
            <el-tag v-if="row.type == 1" type="danger">Электричество</el-tag>
            <el-tag v-if="row.type == 2" type="success">Взносы</el-tag>
          </span>
        </template>
      </el-table-column>
      <el-table-column label="" align="center">
        <template slot-scope="{row}">
          <span>
            <el-tag v-if="row.dubl" type="danger">Повтор</el-tag>
            <el-button type="primary" icon="el-icon-edit" @click="showPayment(row.id)">Подробнее</el-button>
          </span>
        </template>
      </el-table-column>
    </el-table>
    <PaymentInfo :id="showId" />
  </div>
</template>

<script>
import PaymentInfo from './../../component/PaymetnInfo'

export default {
  components: {
    PaymentInfo
  },
  props: {
    list: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      showId: {}
    }
  },
  methods: {
    tableRowClassName({ row, rowIndex }) {
      if (row.error) {
        return 'warning-row'
      }
      return ''
    },
    showStead(id) {
      this.$router.push('/bookkeping/billing_balance_stead/' + id)
    },
    showPayment(id) {
      this.showId = id
      // this.$router.push('/bookkeping/payment_info/' + id)
    }
  }

}
</script>

<style scoped>
  .billing-bank-reestr-table >>> .warning-row {
    background: #fff1f1;
  }
</style>
