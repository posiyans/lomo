<template>
  <div>
    <div class="row justify-around">
      <div class="q-gutter-lg">
        <div class="receipt-block q-gutter-sm q-pa-lg">
          <div><b>Наименование:</b> <span class="props-value">{{ gardening.name }}</span></div>
          <div><b>ИНН:</b> <span class="props-value">{{ gardening.PayeeINN }}</span></div>
          <div><b>Счет:</b> <span class="props-value">{{ gardening.PersonalAcc }}</span></div>
          <div><b>Банк:</b> <span class="props-value">{{ gardening.BankName }}</span></div>
          <div><b>БИК:</b> <span class="props-value">{{ gardening.BIC }}</span></div>
          <div><b>Корсчет:</b> <span class="props-value">{{ gardening.CorrespAcc }}</span></div>
        </div>
        <div>
          <q-separator />
        </div>
        <div class="q-pa-lg">
          <div class="row items-center no-wrap q-col-gutter-sm q-pa-sm justify-end">
            <div>Бланк квитанции на оплату по участкам</div>
            <div>
              <ReceiptDialog />
            </div>
          </div>

          <div class="row items-center no-wrap q-col-gutter-sm q-pa-sm justify-end">
            <div class="mr3">
              Бланк квитанции на оплату c реквизитами
            </div>
            <div>
              <div>
                <q-btn color="primary" label="Скачать" @click="toUrl" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="">
        <PaymentQrCode class="bg-grey-2 q-pa-lg" />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, defineComponent, onBeforeMount, ref } from 'vue'
import { getGardeningInfo } from 'src/Modules/Gardening/api/gardening'
import ReceiptDialog from 'src/Modules/Receipt/components/ReceiptDialog/Btn.vue'
import PaymentQrCode from 'src/Modules/Receipt/components/PaymentQrCode/index.vue'

export default defineComponent({
  components: {
    ReceiptDialog,
    PaymentQrCode
  },
  setup() {
    const gardening = ref({})
    const dialogVisible = ref(false)
    const getData = () => {
      getGardeningInfo()
        .then(res => {
          gardening.value = res.data
        })
    }
    const url = computed(() => {
      return process.env.BASE_API + '/api/v2/receipt/get-clear'
    })
    const toUrl = () => {
      window.open(url.value)
    }
    onBeforeMount(() => {
      getData()
    })
    const showForm = () => {
      dialogVisible.value = true
    }
    return {
      gardening,
      dialogVisible,
      showForm,
      toUrl
    }
  }
})
</script>

<style scoped lang="scss">
.receipt-block {
  font-size: 1.2rem;
}
</style>
