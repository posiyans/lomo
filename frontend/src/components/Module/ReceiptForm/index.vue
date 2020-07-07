<template>
<RightCard header="Квитанция" class="right-welcome">
  <template>
    <div align="center" class="plugin-welcome">
      <div>Бланк квитанции на оплату</div>
      <div>
        <el-link :href="url" type="success"><el-button type="success" size="mini" plain>Скачать</el-button></el-link>
      </div>
      <div>
        <el-button type="success" size="mini" plain @click="qr">QR code</el-button>
      </div>
    </div>
    <el-dialog
      title="QR code для оплаты"
      :visible.sync="centerDialogVisible"
      width="600px"
      center>
      <div align="center"><img :src="urlQRcode"/></div>
      <div align="center">Для оплаты, отсканируйте код в банк клиенте.</div>
      <div align="center"><b>Наименование:</b> {{gardening.name}}</div>
      <div align="center"><b>ИНН:</b> {{gardening.PayeeINN}} <b>Счет:</b> {{gardening.PersonalAcc}}</div>
        <div align="center">
          <b>Банк:</b> {{gardening.BankName}}
        <div align="center">
      </div>
            <b>БИК: </b>{{gardening.BIC}}
              <b>Корсчет:</b> {{gardening.CorrespAcc}}
      </div>
      <span slot="footer" class="dialog-footer">
    <el-button type="primary" @click="centerDialogVisible = false">Ok</el-button>
    </span>
    </el-dialog>
  </template>
</RightCard>
</template>

<script>
  import RightCard from '@/components/RightCard'
  import { getGardeninngInfo } from '@/api/user/gardening.js'
  import { mapGetters } from 'vuex'
  import store from '../../../store'
  export default {
    name: 'RecaiptForm',
    components: {
      RightCard
    },
    data() {
      return {
        centerDialogVisible: false,
        gardening: {}
      }
    },
    mounted() {
    },
    computed: {
      urlQRcode() {
        return process.env.VUE_APP_BASE_API + '/api/v1/receipt/get-qrcode-clear'
      },
      url() {
         return process.env.VUE_APP_BASE_API + '/api/v1/receipt/get-receipt-clear'
      }
    },
    methods:{
      qr(){
        this.fetchGagdening()
        this.centerDialogVisible = true
      },
      fetchGagdening() {
        getGardeninngInfo().then(response =>{
          this.gardening = response.data
        })

       }
    }
  }
</script>

<style scoped>
</style>


