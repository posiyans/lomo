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
        <template #default="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Время" align="center" width="100px">
        <template #default="{row}">
          <ShowTime :time="row.payment_date" format="DD-MM-YYYY" />
        </template>
      </el-table-column>
      <el-table-column label="Сумма" align="center" width="100px">
        <template #default="{row}">
          <div v-if="row.description">
            <div class="relative dib pr2 pt2">
              <div class="absolute top-0 right-0 dark-red fw6">!</div>
              <el-popover
                placement="top"
                width="200"
                trigger="click"
                :content="row.description"
              >
                <span>{{ row.price }}</span>
              </el-popover>
            </div>
          </div>
          <div v-else>
            <span>{{ row.price }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Назначение" align="center">
        <template #default="{row}">
          <span class="do-not-carry f7">{{ row.raw_data[4] }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Номер участка" align="center" width="125px">
        <template #default="{row}">
          <el-tag style="cursor: pointer" @click="showStead(row.stead_id)">
            {{ row.stead.number }}
          </el-tag>
          <span v-if="row.error">
            ({{ row.raw_data[2] }})
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Тип" align="center" width="160px">
        <template #default="{row}">
          <span>
            <div v-if="row.type">
              <el-tag type="success">{{ row.type_name }}</el-tag>
              <!--              <span v-for="i in row.instr_read" :key="i.value">-->
              <!--                <el-tag v-if="i.value" type="success">{{ i.device }}-{{ i.value }}</el-tag>-->
              <!--              </span>-->
            </div>
          </span>
        </template>
      </el-table-column>
      <el-table-column label="" align="center" width="200px">
        <template #default="{row}">
          <span>
            <el-button type="primary" icon="el-icon-info" @click="showPayment(row)">Подробнее</el-button>
          </span>
        </template>
      </el-table-column>
    </el-table>
    <PaymentInfo v-if="showPaymentInfo" :id="itemPayment.id" @close="closePaymentInfo" />
  </div>
</template>

<script>
import PaymentInfo from 'src/Modules/Bookkeeping/pages/BillingPayment/PaymentInfo/index.vue'
import ShowTime from 'components/ShowTime/index.vue'

export default {
  components: {
    PaymentInfo,
    ShowTime
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
      this.$router.push('/admin/bookkeping/billing_balance_stead/' + id)
    },
    showPayment(row) {
      this.itemPayment = row
      this.showPaymentInfo = true
      // this.$router.push('/admin/bookkeping/payment_info/' + id)
    },
    closePaymentInfo() {
      this.showPaymentInfo = false
      this.$emit('reload')
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
