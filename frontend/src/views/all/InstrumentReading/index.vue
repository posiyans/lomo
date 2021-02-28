<template>
  <div>
    <div
      v-loading="loading"
      class="primary-block"
    >
      <div class="primary-block__header">
        <div class="primary-block__header-title">
          Передача показаний онлайн
        </div>
      </div>
      <div class="primary-block__body">

        <el-form ref="ruleForm" label-position="top" label-width="120px">
          <el-form-item label="Тип показаний" :class="{ 'is-error': !ruleForm.type }">
            <el-select v-model="ruleForm.type" placeholder="Показания по">
              <el-option
                v-for="item in typeList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="Участок" prop="name">
            <div class="el-form-item" :class="{ 'is-error': !stead.id }">
              <UserSteadFind :key="key" :stead-id="ruleForm.stead_id" @selectStead="setStead" />
            </div>
          </el-form-item>
          <div v-if="stead.number" class="">Передать показания приборов для участка <span class="fw6 dark-red f4">{{ stead.number }}</span></div>
          <el-form-item v-for="item in devices" :key="item.id" :class="{ 'is-error': item.last >= ruleForm.value[item.id]}">
            <div>
              {{ item.name }}
            </div>
            <div>
              <el-input v-model="ruleForm.value[item.id]" type="number" :placeholder="item.description" />
            </div>
          </el-form-item>
          <el-form-item>
            <el-button @click="resetForm">Сбросить</el-button>
            <el-button type="primary" :disabled="sendDisable" @click="sendData">Отправить</el-button>
          </el-form-item>
        </el-form>
      </div>
      <div class="primary-block__footer">
        <div class="primary-block__footer-back">
          <el-button type="primary" size="mini">Назад</el-button>
        </div>
      </div>
    </div>
    <el-dialog
      title="Показания переданы"
      :visible.sync="receiptShow"
      :close-on-click-modal="false"
      :width="mobile ? '100%' : '600px'"
    >
      <span>Спасибо. Данные приборов успешно сохранены!</span>
      <span slot="footer" class="dialog-footer">
        <el-button type="success" @click="getReceipt">Скачать квитанцию на оплату</el-button>
        <el-button type="primary" @click="receiptShow = false">Закрыть</el-button>
      </span>
    </el-dialog>
    <ReceiptDrawer v-if="drawer" :data-for-receipt="dataForReceipt" :stead-number="stead.number" @close="drawer = false" />
  </div>
</template>

<script>
import { getReceiptTypeList } from '@/api/all/receipt'
import UserSteadFind from '@/components/UserSteadFind'
import ReceiptDrawer from './ReceiptDrawer'
import { getDeviceForStead, sendDataMeteringDeviceForStead } from '@/api/all/instrument-reading'
import Cookies from 'js-cookie'

export default {
  components: { UserSteadFind, ReceiptDrawer },
  data() {
    return {
      showChangeSum: false,
      receiptShow: false,
      drawer: false,
      key: 1,
      devices: [],
      typeList: [],
      loading: false,
      stead: {},
      dataForReceipt: [],
      ruleForm: {
        receipt: true,
        type: '',
        stead_id: '',
        value: {}
      }
    }
  },
  computed: {
    mobile() {
      return this.$store.state.app.device === 'mobile'
    },
    sendDisable() {
      if (this.ruleForm.type && this.stead.id && this.ruleForm.value) {
        return false
      }
      return true
    }
  },
  created() {
    this.getReceipList()
  },
  mounted() {
    this.ruleForm.stead_id = Cookies.get('mySteadId') || ''
    this.key++
  },
  methods: {
    getReceipt() {
      this.drawer = true
    },
    validateNumber(val) {
      return val.match(/^\d+/)
    },
    validateData() {
      let status = true
      for (const key in this.ruleForm.value) {
        if (!this.validateNumber(this.ruleForm.value[key])) {
          status = false
        }
      }
      return status
    },
    sendData() {
      if (this.validateData()) {
        this.ruleForm.stead_id = this.stead.id
        sendDataMeteringDeviceForStead(this.ruleForm)
          .then(response => {
            if (response.data.status) {
              this.$message('Данные успешно сохранены')
              this.showReceiptForm()
              this.dataForReceipt = response.data.data
            } else {
              if (response.data.devices) {
                this.devices = response.data.devices
              }
              this.showErrorMessage(response.data.error_message)
            }
          })
      } else {
        this.$message.error('Показания должны быть числом')
      }
    },
    showReceiptForm() {
      this.receiptShow = true
    },
    resetForm() {
      Cookies.set('mySteadId', '')
      this.ruleForm.stead_id = ''
      this.ruleForm.value = {}
      this.stead = {}
      this.devices = []
      this.key++
    },
    setStead(val) {
      if (val) {
        Cookies.set('mySteadId', val.id)
        this.ruleForm.stead_id = val.id
        this.ruleForm.value = {}
        this.stead = val
        this.getLastReadind()
      } else {
        Cookies.set('mySteadId', '')
        this.ruleForm.value = {}
        this.stead = {}
        this.ruleForm.stead_id = ''
      }
    },
    getReceipList() {
      const data = {
        depends: 2
      }
      getReceiptTypeList(data)
        .then(response => {
          if (response.data.status) {
            this.typeList = response.data.data
            if (this.typeList.length === 1) {
              this.ruleForm.type = this.typeList[0].id
            }
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
    },
    showErrorMessage(data) {
      let message = 'Ошибка при сохранении показаний'
      if (data) {
        message = data
      }
      this.$alert(message, 'Ошибка', {
        confirmButtonText: 'OK',
        type: 'error'
      })
    },
    getLastReadind() {
      getDeviceForStead(this.ruleForm)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            this.devices = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    }
  }
}
</script>

<style scoped>
  .primary-block__body {
    text-indent: 0;
    max-width: 370px;
    padding-left: 50px;
  }
  .steadf {
   /*// border-color: blue;*/
  }
  .steadf >>> input {
    /*//border-color: green;*/
  }
  .el-button + .el-button {
    margin-left: 5px;
  }
  @media screen and (max-width: 700px) {
    .primary-block__body {
      max-width: 340px;
      padding-left: 20px;
    }
  }
</style>
