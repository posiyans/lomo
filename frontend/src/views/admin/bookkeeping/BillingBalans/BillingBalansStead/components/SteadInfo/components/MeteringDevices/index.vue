<template>
  <div>
    <el-button type="primary" size="small" @click="addSteadShow = !addSteadShow">Добавить прибор учета</el-button>
    <AddDevice v-if="addSteadShow" :stead_id="stead.id" @close="addSteadShow = false" @reload="getDevice" />
    <el-table
      :data="devices"
      style="width: 100%"
      border
      class="do-not-carry"
    >
      <el-table-column
        prop="date"
        label="Тип"
      >
        <template slot-scope="{row}">
          <span>{{ row.type_name[0] }} {{ row.type_name[1] }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="date"
        label="Прибор"
      >
        <template slot-scope="{row}">
          <span>{{ row.device_brand }} <i>{{ row.serial_number }}</i></span>
        </template>
      </el-table-column>
      <el-table-column
        prop="date"
        label="Начальные показания"
        align="center"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.initial_data }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="date"
        label="Последние показания"
        align="center"
        width="180"
      >
        <template slot-scope="{row}">
          <div v-if="row.last_reading">
            <div>{{ row.last_reading.value }}</div>
            <div>
              {{ row.last_reading.created_at | moment('DD-MM-YYYY') }}
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="date"
        label="Даты"
        align="center"
        width="300"
      >
        <template slot-scope="{row}">
          <div v-if="row.installation_date">
            <span>
              установки
            </span>
            {{ row.installation_date | moment('DD-MM-YYYY') }}
          </div>
          <div v-if="row.verification_date">
            <span>
              годен до
            </span>
            {{ row.verification_date | moment('DD-MM-YYYY') }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="date"
        label="Примечание"
        width="180"
      >
        <template slot-scope="{row}">
          <span>{{ row.descriptions }}</span>
        </template>
      </el-table-column>
      <el-table-column
        prop="date"
        label=""
        width="180"
      >
        <template slot-scope="{row}">
          <el-tag v-if="row.active" type="success">Активный</el-tag>
          <el-tag v-if="!row.active" type="danger">Не рабочий</el-tag>
          <el-button type="primary" icon="el-icon-edit" size="mini" @click="editDevice(row)" />
        </template>
      </el-table-column>
    </el-table>
    <el-dialog
      title="Редактироовать"
      :visible.sync="dialogVisible"
      :width="mobile ? '100%' : '600px'"
    >
      <el-form
        ref="editForm"
        :model="editRow"
        :rules="rules"
        label-width="120px"
        class="demo-ruleForm"
        label-position="top"
      >
        <el-form-item label="Начальные показания" prop="initial_data">
          <el-input v-model="editRow.initial_data" type="number" />
        </el-form-item>
        <el-form-item label="Дата следующей поверки" prop="verification_date">
          <el-date-picker
            v-model="editRow.verification_date"
            type="date"
            format="dd-MM-yyyy"
            value-format="yyyy-MM-dd"
            :picker-options="datePickerOptions"
            placeholder="Дата следующей поверки"
          />
        </el-form-item>
        <el-form-item label="Примечание" prop="descriptions">
          <el-input v-model="editRow.descriptions" type="textarea" />
        </el-form-item>
        <el-form-item label="Статус" prop="active">
          <el-switch
            v-model="editRow.active"
            style="display: block"
            active-color="#13ce66"
            inactive-color="#ff4949"
            active-text="Активный"
            inactive-text="Не рабочий"
          />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Закрыть</el-button>
        <el-button type="primary" @click="updateDevice">Сохранить</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import AddDevice from './AddDevice'
import { fetchDevicesForStead, updateDevices } from '@/api/admin/bookkeping/metering-device'
export default {
  components: { AddDevice },
  props: {
    stead: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      dialogVisible: false,
      editRow: {},
      addSteadShow: false,
      stead_loc: {
        descriptions: {},
        options: {}
      },
      id: '',
      devices: [],
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
      },
      datePickerOptions: {
        firstDayOfWeek: 1
        // disabledDate(time) {
        //   return time.getTime() < Date.now()
        // }
      }

    }
  },
  computed: {
    mobile() {
      return this.$store.state.app.device === 'mobile'
    }
  },
  mounted() {
    this.stead_loc = this.stead
    this.id = this.$route.params && this.$route.params.id
    this.getDevice()
  },
  methods: {
    editDevice(row) {
      this.editRow = row
      this.dialogVisible = true
    },
    updateDevice() {
      updateDevices(this.editRow.id, this.editRow)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            this.dialogVisible = false
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
          this.getDevice()
        })
    },
    closeAdd() {
      this.addSteadShow = false
      this.getDevice()
    },
    getDevice() {
      fetchDevicesForStead({ stead_id: this.id })
        .then(response => {
          if (response.data.status) {
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
  table {
    border-collapse: collapse;
    margin: 25px;
  }
  td, th {
    border: 1px solid #606266;
    padding: 5px 10px;
    text-align: center;
    color: #000000;
  }
</style>
