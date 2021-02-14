<template>
  <div style="background-color: #ffffff">
    <el-row :gutter="10" align="center">
      <el-col :span="24" style="text-align: center; padding: 20px;">Бланк квитанции на оплату</el-col>
    </el-row>
    <el-row :gutter="10" align="center">
      <el-col :span="24" style="text-align: center; padding: 20px;">
        <el-link :href="process.env.VUE_APP_BASE_API + '/../user/receipt/get-receipt-clear'" type="success"><el-button type="success" size="mini" plain @click="download">Скачать</el-button></el-link>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
    }
  },
  methods: {
    download() {

    }
  }
}
</script>

<style scoped>

</style>
