<template>
  <div class="primary-block">
    <div class="primary-block__header">
      <div class="primary-block__header-title">
        Реквизиты для оплаты
      </div>
    </div>
    <div class="primary-block__body">
      <div class="bg-light-gray pa2 pr4 hl br1 mb4">
        <div><b>Наименование:</b> <span class="props-value">{{ gardening.name }}</span></div>
        <div><b>ИНН:</b> <span class="props-value">{{ gardening.PayeeINN }}</span></div>
        <div><b>Счет:</b> <span class="props-value">{{ gardening.PersonalAcc }}</span></div>
        <div><b>Банк:</b> <span class="props-value">{{ gardening.BankName }}</span></div>
        <div><b>БИК:</b> <span class="props-value">{{ gardening.BIC }}</span></div>
        <div><b>Корсчет:</b> <span class="props-value">{{ gardening.CorrespAcc }}</span></div>
      </div>
      <div class="mb5">
        <div class="flex flex-wrap mb3">
          <div class="mr3">Бланк квитанции на оплату <b>взносов</b> по участкам</div>
          <div>
            <el-button type="primary" size="mini" plain @click="showForm()">Скачать</el-button>
          </div>
        </div>
        <ShowForm2 v-if="dialogVisible" @close="dialogVisible = false" />
        <div class="flex flex-wrap mb3">
          <div class="mr3">
            Бланк квитанции на оплату c реквизитами
          </div>
          <div>
            <el-link :href="url">
              <el-button type="primary" size="mini" plain>
                Скачать
              </el-button>
            </el-link>
          </div>
        </div>
      </div>

      <div class="tc pa2 br4 bg-white ba b--mid-gray qr-code fw6">
        <div class="tc f4 pa2">QR code для оплаты</div>
        <div align="center"><img :src="urlQRcode"></div>
        <div class="pt3 pb3">Для оплаты, отсканируйте код в банк клиенте.</div>
      </div>
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
  .primary-block__body {
    text-indent: 0;
    padding-bottom: 50px;
  }
  .props-main {
    padding-left: 25px;
  }
  .props div {
    padding-top: 12px;
  }
  .props-value {
    color: #1b3958;
  }
  .qr-code {
    max-width: 340px;
    margin: 0 auto;
  }
</style>
