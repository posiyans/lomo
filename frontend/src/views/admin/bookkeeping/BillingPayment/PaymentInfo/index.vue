<template>
  <div v-if="load" class="app-container">
    Детали платежа № {{ id }} от {{ payment.payment_date | moment('DD-MM-YYYY') }}
    <div>
      Дата оплаты: {{ payment.payment_date }}
    </div>
    <div>
      Назначение платежа: {{ payment.discription }}
    </div>
    <div>
      ФИО: {{ payment.raw_data[6] }}
    </div>
    <div>
      Участок: <el-button size="mini" type="warning" plain @click="showChangeStead">{{ payment.stead.number }}</el-button> ({{ payment.raw_data[5] }})
      <div v-if="changeSteadVizible">
        <UserSteadFind :user-stead="payment.stead_id" @selectStead="setNewStead" />
        <el-button type="success" size="mini" @click="changeStead">Ок</el-button>
      </div>
    </div>
    <div>
      Сумма: {{ payment.price }} руб.
    </div>
    <div>
      Оплата:
      <el-tag type="danger" :effect="payment.type | type1EffectFilter" @click="selectElect()">
        <i v-if="payment.type == 1" class="el-icon-check" />
        Электоэнергия
      </el-tag>
      <el-tag type="success" :effect="payment.type | type2EffectFilter" @click="payment.type = 2">
        <i v-if="payment.type == 2" class="el-icon-check" />
        Взносы
      </el-tag>
      <div v-if="payment.type == 1">
        <div v-if="payment.raw_data.meterReading1" class="text-red mt2 mb2">
          Показания день: <b>{{ payment.raw_data.meterReading1 }}</b> кв*ч
        </div>
        <div v-if="payment.raw_data.meterReading2" class="text-green mt2 mb2">
          Показания ночь: <b>{{ payment.raw_data.meterReading2 }}</b> кв*ч
        </div>
      </div>
      <el-button type="success" @click="savePayment">Сохранить</el-button>
    </div>
    <MeterReading
      v-if="dialogMeterReadingFormVisible"
      :meter-reading1="payment.raw_data.meterReading1"
      :meter-reading2="payment.raw_data.meterReading2"
      :discription="payment.discription"
      @save="setMeterReading"
      @close="closeMetering"
    />
  </div>
</template>

<script>
import { fetchPaymentInfo, updatePaymentInfo } from '@/api/admin/bookkeping/payment'
import MeterReading from '@/components/MeterReading'
import UserSteadFind from '@/components/UserSteadFind'

export default {
  filters: {
    type1EffectFilter(val) {
      if (val === 1) {
        return 'dark'
      }
      return 'plain'
    },
    type2EffectFilter(val) {
      if (val === 2) {
        return 'dark'
      }
      return 'plain'
    }
  },
  components: {
    MeterReading,
    UserSteadFind
  },
  data() {
    return {
      id: null,
      load: false,
      dialogMeterReadingFormVisible: false,
      payment: {},
      changeSteadVizible: false,
      newStead: null
    }
  },
  created() {
    this.id = this.$route.params && this.$route.params.id
    this.getData()
  },
  methods: {
    changeStead(data) {
      this.$confirm('ВЫ действительно хотите сменить участок для платежа?', 'Внимание!!!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'error'
      }).then(() => {
        this.payment.stead_id = this.newStead.id
        this.payment.stead_number = this.newStead.number
        this.changeSteadVizible = false
      }).catch(() => {
        this.changeSteadVizible = false
      })
    },
    setNewStead(data) {
      this.newStead = data
    },
    showChangeStead() {
      this.changeSteadVizible = true
    },
    getData() {
      this.load = false
      fetchPaymentInfo(this.id).then(response => {
        if (response.data.data) {
          this.payment = response.data.data
          this.load = true
        }
      })
    },
    selectElect() {
      console.log('fsdfsdfs')
      this.payment.type = 1
      this.dialogMeterReadingFormVisible = true
    },
    closeMetering() {
      this.dialogMeterReadingFormVisible = false
    },
    setMeterReading(val) {
      if (val[0]) {
        this.payment.raw_data.meterReading1 = val[0]
      }
      if (val[1]) {
        this.payment.raw_data.meterReading2 = val[1]
      }
    },
    savePayment() {
      updatePaymentInfo(this.payment.id, this.payment).then(response => {
        if (response.data.status) {
          this.$message('Данные сохранены')
        } else {
          this.$message.error('Ошибка при сохранении')
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
