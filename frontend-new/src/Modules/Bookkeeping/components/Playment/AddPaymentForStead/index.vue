<template>
  <div>
    <el-form ref="addInvoice" :model="ruleForm" :rules="rules" label-width="120px" label-position="top">
      <el-form-item label="Участок" prop="stead">
        <el-input v-model="ruleForm.stead.number" :readonly="true" />
      </el-form-item>
      <el-form-item label="Тип платежа" prop="type">
        <el-select
          v-model="ruleForm.type"
          placeholder="Платеж"
          @change="changeType"
        >
          <el-option
            v-for="item in receiptTypes"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Назначение платежа" prop="title">
        <el-input v-model="ruleForm.title" />
      </el-form-item>
      <el-form-item label="Cумма" prop="price">
        <el-input v-model="ruleForm.price" />
      </el-form-item>
      <el-form-item label="Примечание" prop="description">
        <el-input v-model="ruleForm.description" type="textarea" />
      </el-form-item>
      <el-form-item>
        <el-button @click="resetForm">Отмена</el-button>
        <el-button type="primary" @click="submitForm">Добавить</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { fetchReceiptTypeList } from 'src/Modules/Receipt/api/receiptAdminApi.js'
import { addPaymentForStead } from 'src/Modules/Bookkeeping/api/paymentApi.js'

export default {
  props: {
    stead: {
      type: Object,
      default: () => ({})
    }
  },

  data() {
    const validatePrice = (rule, val, callback) => {
      const value = val.replace(',', '.')
      if (isNaN(parseFloat(value)) && !isFinite(value)) {
        callback(new Error('Сумма должна быть числом'))
      }
      if (value > 0) {
        callback()
      } else {
        callback(new Error('Сумма должна быть больше 0'))
      }
    }
    return {

      receiptTypes: [],
      ruleForm: {
        stead: {},
        title: '',
        type: '',
        price: 0,
        description: ''
      },
      rules: {
        title: [
          { required: true, message: 'Введите назначение платежа', trigger: 'blur' }
        ],
        stead: [
          { required: true, message: '', trigger: 'change' }
        ],
        type: [
          { required: true, message: 'Укажите тип платежа', trigger: 'change' }
        ],
        price: [
          { required: true, trigger: 'change', validator: validatePrice }
        ],
        desc: [
          { max: 255, message: 'Длина не должна превышать 250 символов', trigger: 'blur' }
        ]
      }
    }
  },
  mounted() {
    this.getTypeList()
    this.ruleForm.stead = {
      id: this.stead.id,
      number: this.stead.number
    }
  },
  methods: {
    changeType() {
      this.ruleForm.title = this.receiptTypes.filter(i => i.id === this.ruleForm.type)[0].name
    },
    addPayment() {
      this.$confirm('Добавить платеж через кассу на ' + this.ruleForm.price + ' руб?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        addPaymentForStead(this.ruleForm)
          .then(response => {
            if (response.data.status) {
              this.$message('Платеж успешно добавлен')
              this.$emit('close')
              this.$refs.addInvoice.resetFields()
              this.ruleForm.stead = {
                id: this.stead.id,
                number: this.stead.number
              }
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      }).catch(() => {
      })
    },
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receiptTypes = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    submitForm(formName) {
      this.$refs.addInvoice.validate((valid) => {
        if (valid) {
          this.addPayment()
        } else {
          this.$message.error('Заполнены не все обязательные поля')
        }
      })
    },
    resetForm(formName) {
      this.$emit('close')
      this.$refs.addInvoice.resetFields()
      this.ruleForm.stead = this.stead
    }
  }
}
</script>

<style scoped>

</style>
