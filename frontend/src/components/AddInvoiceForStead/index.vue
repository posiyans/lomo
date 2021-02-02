<template>
  <div>
    <el-form ref="addInvoice" :model="ruleForm" :rules="rules" label-width="120px" label-position="top">
      <el-form-item label="Назначение счета" prop="title">
        <el-input v-model="ruleForm.title" />
      </el-form-item>
      <el-form-item label="Участок" prop="stead">
        <el-input v-model="ruleForm.stead.number" :readonly="true" />
      </el-form-item>
      <el-form-item label="Тип платежа" prop="type">
        <el-select v-model="ruleForm.type" placeholder="Платеж">
          <el-option
            v-for="item in receiptTypes"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Cумма" prop="price">
        <el-input v-model="ruleForm.price" />
      </el-form-item>
      <el-form-item label="Примечание" prop="discription">
        <el-input v-model="ruleForm.discription" type="textarea" />
      </el-form-item>
      <el-form-item>
        <el-button @click="resetForm">Отмена</el-button>
        <el-button type="primary" @click="submitForm">Добавить</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'
import { addInvoiceForStead } from '@/api/admin/bookkeping/invoice'

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
      console.log(+value)
      console.log(typeof +value)
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
        discription: ''
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
    this.ruleForm.stead = this.stead
  },
  methods: {
    addInvoice() {
      this.$confirm('Добавить счет на ' + this.ruleForm.price + ' руб ?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        addInvoiceForStead(this.ruleForm)
          .then(response => {
            if (response.data.status) {
              this.$message('Счет успешно добавлен')
              this.$emit('close')
              this.$refs['addInvoice'].resetFields()
              this.ruleForm.stead = this.stead
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
      this.$refs['addInvoice'].validate((valid) => {
        if (valid) {
          this.addInvoice()
        } else {
          this.$message.error('Заполнены не все обязательные поля')
        }
      })
    },
    resetForm(formName) {
      this.$emit('close')
      this.$refs['addInvoice'].resetFields()
      this.ruleForm.stead = this.stead
    }
  }
}
</script>

<style scoped>

</style>
