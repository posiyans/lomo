<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">

    <el-table-column label="Участкок" align="center">
      <template slot-scope="{row}">
        <el-tag style="cursor: pointer" @click="showStead(row.stead_id)">{{ row.stead_number }}</el-tag><span v-if="row.stead_number != row.raw_data.val5">({{ row.raw_data.val5 }}) </span>
        <el-tag v-if="row.type == 1" type="danger" @click="showInvoice(row.id)">Элек</el-tag>
        <el-tag v-if="row.type == 2" type="success" @click="showInvoice(row.id)">Взнос</el-tag>
      </template>
    </el-table-column>
    <el-table-column label="Время" align="center">
      <template slot-scope="{row}">
        <span>{{ row.payment_date | moment("DD-MM-YYY HH:mm") }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Назначение" align="center">
      <template slot-scope="{row}">
        <span>{{ row.description }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Сумма" align="center">
      <template slot-scope="{row}">
        <span>{{ row.price }}</span>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: {
    list: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
    }
  },
  methods: {
    showStead(id) {
      this.$router.push('/admin/bookkeping/billing_balance_stead/' + id)
    },
    showInvoice(id) {
      this.$router.push('/admin/bookkeping/invoice_info/' + id)
    }
  }

}
</script>

<style scoped>

</style>
