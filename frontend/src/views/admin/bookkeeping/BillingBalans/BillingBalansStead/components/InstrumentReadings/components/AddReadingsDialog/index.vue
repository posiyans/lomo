<template>
  <div>
    <el-dialog
      title="Добавить показания"
      :visible.sync="dialogVisible"
      width="30%"
      @close="handleClose()"
    >
      <div>
        <el-form ref="form" label-position="top">
          <el-form-item label="Прибор">
            <el-select
              v-model="listQuery.device_id"
              placeholder="Тип прибора"
              style="width: 320px;"
            >
              <el-option
                v-for="item in devices"
                :key="item.id"
                :label="item.type_name[1]"
                :value="item.id"
              >
                <span style="float: left">{{ item.type_name[1] }}</span>:
                <span style="float: right; color: #8492a6; font-size: 13px">SN:{{ item.serial_number }}</span>
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Показания">
            <el-input v-model="listQuery.value" type="number" :readonly="!listQuery.device_id" placeholder="Показания" />
          </el-form-item>
          <el-form-item label="от какой даты">
            <el-date-picker
              v-model="listQuery.date"
              type="date"
              format="dd-MM-yyyy"
              :picker-options="{ firstDayOfWeek: 1}"
              value-format="yyyy-MM-dd"
              placeholder="от даты"
            />
          </el-form-item>
        </el-form>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Отмена</el-button>
        <el-button type="primary" @click="saveData">Ок</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchDevicesForStead } from '@/api/admin/bookkeping/metering-device'
import { addInstrumentReadings } from '@/api/admin/bookkeping/communal'
export default {
  props: {
    steadId: {
      type: [String, Number],
      default: ''
    }
  },
  data() {
    return {
      dialogVisible: true,
      typeList: [],
      itemsTypeList: [],
      devices: [],
      listQuery: {
        test: true,
        device_id: '',
        stead_id: this.stead_id,
        value: '',
        date: this.$moment().format('YYYY-MM-DD')
      }
    }
  },
  mounted() {
    // console.log('mounted')
    this.getDevice()
  },
  methods: {
    handleClose() {
      this.$emit('close')
    },
    getDevice() {
      fetchDevicesForStead({ stead_id: this.id })
        .then(response => {
          if (response.data.status) {
            this.devices = response.data.data
            if (this.devices.length === 1) {
              this.listQuery.device_id = this.devices[0].id
            }
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    saveData() {
      if (this.listQuery.value > 0) {
        addInstrumentReadings(this.stead_id, this.listQuery)
          .then(response => {
            if (response.data.status) {
              if (response.data.error) {
                this.$alert(response.data.data, 'Внимание!', {
                  confirmButtonText: 'Ок',
                  type: 'warning',
                  callback: action => {
                    this.dialogVisible = false
                  }
                })
              } else {
                this.$message('Данные успешно сохранены')
                this.dialogVisible = false
              }
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })
      } else {
        this.$message.error('Показания должны быть больше нуля')
      }
    }
  }
}
</script>

<style scoped>

</style>
