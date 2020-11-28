<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column label="№" align="center" width="60px">
      <template slot-scope="{row}">
        <span>{{ row.id }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Номер участка" align="center" width="125px">
      <template slot-scope="{row}">
        <el-tag style="cursor: pointer" @click="showStead(row.stead_id)">{{ row.stead_number }}</el-tag><span v-if="row.stead_number != row.raw_data.val5">({{ row.raw_data.val5 }}) </span>
      </template>
    </el-table-column>
    <el-table-column label="Время" align="center">
      <template slot-scope="{row}">
        <span>{{ row.payment_date | moment("DD-MM-YYY HH:mm") }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Назначение" align="center">
      <template slot-scope="{row}">
        <span>{{ row.discription }}</span>
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
          <el-button type="primary" icon="el-icon-edit" @click="showInvoice(row.id)">Подробнее</el-button>
        </span>
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
      this.$router.push('/bookkeping/billing_balance_stead/' + id)
    },
    shonvoice(id) {
      this.$router.push('/bookkeping/invoice_info/' + id)
    }
  }

}
</script>

<style scoped>

</style>
