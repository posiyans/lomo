<template>
  <div class="pt3">
    <div class="pa3">
      {{ device }}

    </div>
    <el-form
      ref="addForm"
      :model="device"
      :rules="rules"
      label-width="120px"
      class="demo-ruleForm"
      label-position="top"
    >
      <el-form-item label="Тип прибора" prop="type_id">
        <el-select
          v-model="primaryType"
          placeholder="Тип прибора"
          @change="getItemsTypeList"
        >
          <el-option
            v-for="item in typeList"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
        <el-select v-model="device.type_id" placeholder="Тип прибора">
          <el-option
            v-for="item in itemsTypeList"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Модель прибора" prop="device_brand">
        <el-input v-model="device.device_brand" />
      </el-form-item>
      <el-form-item label="Серийный номер" prop="serial_number">
        <el-input v-model="device.serial_number" />
      </el-form-item>
      <el-form-item label="Начальные показания" prop="initial_data">
        <el-input v-model="device.initial_data" type="number" />
      </el-form-item>
      <el-form-item label="Дата установки" prop="installation_date">
        <el-date-picker
          v-model="device.installation_date"
          type="date"
          format="dd-MM-yyyy"
          value-format="yyyy-MM-dd"
          :picker-options="datePickerOptions"
          placeholder="Дата установки"
        />
      </el-form-item>
      <el-form-item label="Дата следующей поверки" prop="verification_date">
        <el-date-picker
          v-model="device.verification_date"
          type="date"
          format="dd-MM-yyyy"
          value-format="yyyy-MM-dd"
          :picker-options="datePickerOptions"
          placeholder="Дата следующей поверки"
        />
      </el-form-item>
      <el-form-item label="Примечание" prop="discriptions">
        <el-input v-model="device.descriptions" type="textarea" />
      </el-form-item>
      <el-form-item>
        <el-button @click="close">Отмена</el-button>
        <el-button type="primary" @click="submitForm">Добавить</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { fetchReceiptTypeList, getReceiptTypeInfo } from '@/api/admin/setting/receipt'
import { addDeviceForStead } from '@/api/admin/bookkeping/metering-device'
export default {
  props: {
    steadId: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      device: {
        type_id: '',
        stead_id: '',
        initial_data: 0,
        serial_number: '',
        device_brand: '',
        installation_date: '',
        verification_date: '',
        descriptions: ''
      },
      id: '',
      datePickerOptions: {
        firstDayOfWeek: 1
        // disabledDate(time) {
        //   return time.getTime() < Date.now()
        // }
      },
      typeList: [],
      itemsTypeList: [],
      primaryType: '',
      rules: {
        type_id: [
          { required: true, message: 'Укажите тип прибора', trigger: 'change' }
        ],
        serial_number: [
          { required: true, message: 'Укажите серийный номер прибора', trigger: 'blur' },
          { min: 3, message: 'Более  3 символов', trigger: 'blur' }
        ],
        device_brand: [
          { required: true, message: 'Укажите серийный номер прибора', trigger: 'blur' },
          { min: 3, message: 'Более  3 символов', trigger: 'blur' }
        ],
        installation_date: [
          { required: true, message: 'Укажите дату установки', trigger: 'change' }
        ],
        initial_data: [
          { required: true, message: 'Укажите начальные показания прибора', trigger: 'change' }
        ],
        verification_date: [
          { required: true, message: 'PУкажите дату следующей поверки прибора', trigger: 'change' }
        ],
        descriptions: [
          { max: 250, message: 'Не более 250 символов', trigger: 'blur' }
        ]
      }
    }
  },
  mounted() {
    this.getTypeList()
    this.id = this.$route.params && this.$route.params.id
    this.device.stead_id = this.id
  },
  methods: {
    close() {
      this.$emit('close')
    },
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.typeList = []
            response.data.data.forEach(item => {
              if (item.depends === 2) {
                this.typeList.push(item)
              }
            })
            if (this.typeList.length === 1) {
              this.primaryType = this.typeList[0].id
              this.getItemsTypeList()
            }
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    getItemsTypeList() {
      console.log('getItemsTypeList')
      this.device.type_id = ''
      getReceiptTypeInfo(this.primaryType)
        .then(response => {
          if (response.data.status) {
            // this.$message('Данные успешно сохранены')
            this.itemsTypeList = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    submitForm() {
      console.log('submit')
      this.$refs['addForm'].validate((valid) => {
        if (valid) {
          this.addDevice()
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    addDevice() {
      addDeviceForStead(this.device)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            this.$emit('reload')
            this.close()
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

</style>
