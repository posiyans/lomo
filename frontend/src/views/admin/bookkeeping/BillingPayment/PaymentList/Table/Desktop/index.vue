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
          <span class="do-not-carry">{{ row.raw_data[7] }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Сумма" align="center">
        <template slot-scope="{row}">
          <div v-if="row.discription">
            <div class="relative dib pr2 pt2">
              <div class="absolute top-0 right-0 dark-red fw6">!</div>
              <el-popover
                placement="top"
                width="200"
                trigger="click"
                :content="row.discription">
                <span slot="reference">{{ row.price }}</span>
              </el-popover>
            </div>
          </div>
          <div v-else>
            <span>{{ row.price }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Тип" align="center">
        <template slot-scope="{row}">
          <span>
            <el-tag v-if="row.type == 1" type="danger">Электричество</el-tag>
            <el-tag v-if="row.type == 2" type="success">Взносы</el-tag>
            <div v-if="row.type == 1">
              <span v-for="i in row.instr_read" :key="i.value">
                <el-tag v-if="i.value" type="success">{{ i.device }}-{{i.value}}</el-tag>
              </span>
            </div>
          </span>
        </template>
      </el-table-column>
      <el-table-column label="" align="center">
        <template slot-scope="{row}">
          <span>
            <el-tag v-if="row.dubl" type="danger">Повтор</el-tag>
            <el-button type="primary" icon="el-icon-edit" @click="showPayment(row)">Подробнее</el-button>
          </span>
        </template>
      </el-table-column>
    </el-table>
    <PaymentInfo v-if="showPaymentInfo" :payment="itemPayment" @close="closePaymentInfo"/>
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
      showId: {},
      itemPayment: {},
      showPaymentInfo: false
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
    showPayment(row) {
      this.itemPayment = row
      this.showPaymentInfo = true
      // this.$router.push('/bookkeping/payment_info/' + id)
    },
    closePaymentInfo() {
      this.showPaymentInfo = false
    }
  }

}
</script>

<style scoped>
  .billing-bank-reestr-table >>> .warning-row {
    background: #fff1f1;
  }
  .item {
    margin-top: 10px;
    margin-right: 40px;
  }
  .item >>> .el-badge__content.is-fixed {
    right: 0;
  }
</style>
