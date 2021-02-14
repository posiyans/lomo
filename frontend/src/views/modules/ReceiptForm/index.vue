<template>
  <div class="app-container">
    <div class="page-title">Реквизиты для оплаты</div>
    <div class="props bg-white dib pa2">
      <div><b>Наименование:</b> <span class="props-value">{{ gardening.name }}</span></div>
      <div><b>ИНН:</b> <span class="props-value">{{ gardening.PayeeINN }}</span></div>
      <div><b>Счет:</b> <span class="props-value">{{ gardening.PersonalAcc }}</span></div>
      <div><b>Банк:</b> <span class="props-value">{{ gardening.BankName }}</span></div>
      <div><b>БИК:</b> <span class="props-value">{{ gardening.BIC }}</span></div>
      <div><b>Корсчет:</b> <span class="props-value">{{ gardening.CorrespAcc }}</span></div>
    </div>
    <div class="blank">
      Бланк квитанции на оплату <b>взносов</b> по участкам
      <el-button type="primary" size="mini" plain @click="showForm()">Скачать</el-button>
    </div>
    <ShowForm2 v-if="dialogVisible" @close="dialogVisible = false" />
    <div class="blank">
      Бланк квитанции на оплату c реквизитами
      <span class="pl2">
        <el-link :href="url">
          <el-button type="primary" size="mini" plain>
            Скачать
          </el-button>
        </el-link>
      </span>
    </div>
    <div class="qr-code">
      <h4 align="center">QR code для оплаты</h4>
      <div align="center"><img :src="urlQRcode"></div>
      <div>Для оплаты, отсканируйте код в банк клиенте.</div>
    </div>
  </div>
</template>

<script>
import { getGardeninngInfo } from '@/api/user/gardening.js'
import { getSteadsList } from '@/api/user/stead.js'
import ShowForm2 from './ShowForm2'

export default {
  name: 'ModulesReceiptForm',
  components: {
    ShowForm2
  },
  data() {
    return {
      centerDialogVisible: false,
      gardening: {},
      dialogVisible: false,
      getSteadsList: [],
      stead: ''
    }
  },
  computed: {
    urlQRcode() {
      return process.env.VUE_APP_BASE_API + '/api/v1/receipt/get-qrcode-clear'
    },
    url() {
      return process.env.VUE_APP_BASE_API + '/api/v1/receipt/get-clear'
    }
  },
  mounted() {
    this.fetchGagdening()
  },
  methods: {
    setStead(val) {
      this.stead = val
    },
    fetchGagdening() {
      getGardeninngInfo().then(response => {
        this.gardening = response.data
      })
    },
    showForm() {
      this.dialogVisible = true
    },
    findStead() {
      const data = {
        query: this.stead
      }
      getSteadsList(data)
        .then(response => {
          this.steadsListMax = response.data
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
