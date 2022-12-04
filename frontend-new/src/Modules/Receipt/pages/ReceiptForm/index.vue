<template>
  <div>
    <div class="text-h6 text-weight-bold q-py-md">
      Реквизиты для оплаты
    </div>
    <q-separator />

    <div class="q-py-md">
      <GardeningRequisite class="bg-grey-3 q-pa-md q-gutter-sm" />
      <div class="row justify-around">
        <div class="q-pa-lg">
          <div class="row items-center no-wrap q-col-gutter-sm q-pa-sm">
            <div>Бланк квитанции на оплату <b>взносов</b> по участкам</div>
            <div>
              <el-button type="primary" size="small" plain @click="showForm()">Скачать</el-button>
            </div>
          </div>
          <ReceiptDialog v-if="dialogVisible" @close="dialogVisible = false" />
          <div class="row items-center no-wrap q-col-gutter-sm q-pa-sm">
            <div class="mr3">
              Бланк квитанции на оплату c реквизитами
            </div>
            <div>
              <el-link :href="url">
                <el-button type="primary" size="small" plain>
                  Скачать
                </el-button>
              </el-link>
            </div>
          </div>
        </div>
        <div class="row justify-center">
          <div class="text-center rounded-borders q-ma-lg bg-grey-2">
            <div class="text-h4 q-pa-md">QR code для оплаты</div>
            <div class="q-ma-lg q-pa-md"><img :src="urlQRcode"></div>
            <div class="q-pa-md">Для оплаты, отсканируйте код в банк клиенте.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ReceiptDialog from 'src/Modules/Receipt/components/ReceiptDialog/index.vue'
import GardeningRequisite from 'src/Modules/Gardening/components/GardeningRequisite/index.vue'

export default {
  components: {
    ReceiptDialog,
    GardeningRequisite
  },
  data () {
    return {
      centerDialogVisible: false,
      dialogVisible: false,
      stead: '321'
    }
  },
  computed: {
    urlQRcode () {
      return process.env.BASE_API + '/api/v1/receipt/get-qrcode-clear'
    },
    url () {
      return process.env.BASE_API + '/api/v1/receipt/get-clear'
    }
  },
  mounted () {
  },
  methods: {
    showForm () {
      this.dialogVisible = true
    }
  }
}
</script>

<style scoped>

</style>
