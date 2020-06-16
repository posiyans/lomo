<template>
  <div class="app-container">
    <h2>Реквизиты для оплаты</h2>
    <div class="props">
      <div><b>Наименование:</b> <span class="props-value">{{gardening.name}}</span></div>
      <div><b>ИНН:</b> <span class="props-value">{{gardening.PayeeINN}}</span></div>
      <div><b>Счет:</b> <span class="props-value">{{gardening.PersonalAcc}}</span></div>
      <div><b>Банк:</b> <span class="props-value">{{gardening.BankName}}</span></div>
      <div><b>БИК:</b> <span class="props-value">{{gardening.BIC}}</span></div>
      <div><b>Корсчет:</b> <span class="props-value">{{gardening.CorrespAcc}}</span></div>
    </div>
    <template>
    <div class="blank">
      <b>Бланк квитанции на оплату</b>
        <el-link :href="url" ><el-button type="primary" size="mini" plain>Скачать</el-button></el-link>
    </div>
    <div class="qr-code">
      <h4 align="center">QR code для оплаты</h4>
      <div align="center"><img :src="urlQRcode"/></div>
      <div align="center">Для оплаты, отсканируйте код в банк клиенте.</div>
    </div>
  </template>
  </div>
</template>

<script>
import { getGardeninngInfo } from '@/api/user/gardening.js'
import { mapGetters } from 'vuex'
import store from '../../../store'
export default {
  name: 'RecaiptForm',
  components: {
  },
  data() {
    return {
      centerDialogVisible: false,
      gardening: {}
    }
  },
  mounted() {
    this.fetchGagdening()
  },
  computed: {
    urlQRcode() {
      return process.env.VUE_APP_BASE_API + '/user/receipt/get-qrcode-clear'
    },
    url() {
       return process.env.VUE_APP_BASE_API + '/user/receipt/get-receipt-clear'
    }
  },
  methods:{
    fetchGagdening() {
      getGardeninngInfo().then(response => {
        this.gardening = response.data
      })
     }
  }
}
</script>

<style scoped>
  .props-main {
    padding-left: 25px;
  }
  .props div {
    padding-top: 12px;
  }
  .props-value {
    color: #1b3958;
  }
  .blank {
    padding-top: 20px;
  }
  .qr-code {
    align-content: center;
    margin-top: 20px;
    border-radius: 10px;
    padding-left: 20px;
    padding-top: 5px;
    padding-bottom: 20px;
    background-color: white;
    width: 350px;
  }
</style>


