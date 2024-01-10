<template>
  <div>
    <el-dialog
      title="Платеж"
      v-model="dialogVisible"
      top="15px"
      @close="close"
    >
      <table border="1" class="do-not-carry">
        <tr class="bg-black-05">
          <th>Поле</th>
          <th>Значение</th>
          <th>
            <div v-if="payment.payment_type === 2" class="red">
              Платеж в кассу
            </div>
          </th>
        </tr>
        <tr>
          <td>id</td>
          <td>{{ payment.id }}</td>
          <td />
        </tr>
        <tr>
          <td>Дата</td>
          <td>
            <ShowTime :time="payment.payment_date" format="DD-MM-YYYY HH:mm" />
          </td>
          <td />
        </tr>
        <tr class="black" :class="{'bg-washed-red': stead_error}">
          <td>Участок</td>
          <td>
            <div v-if="!findSteadShow">
              <span v-if="stead_error">
                {{ payment.raw_data[2] }} -->
                {{ payment.stead.number }}
              </span>
              <span v-else @click="putStead(payment.stead.id)">
                {{ payment.stead.number }}
              </span>
            </div>
            <div v-if="findSteadShow" class="flex">
              <UserSteadFind :stead_id="payment.stead.id" @selectStead="setStead" />
              <el-button type="success" size="small" @click="saveNewStead">Ok</el-button>
            </div>
          </td>
          <td>
            <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="changeStead" />
          </td>
        </tr>
        <tr>
          <td>ФИО</td>
          <td>
            {{ payment.raw_data[3] }}
          </td>
          <td />
        </tr>
        <tr>
          <td>Сумма</td>
          <td>{{ payment.price }}</td>
          <td />
        </tr>
        <tr>
          <td>Назначение</td>
          <td>{{ payment.raw_data[4] }}</td>
          <td />
        </tr>
        <tr class="black" :class="{'bg-washed-red': type_error}">
          <td>Тип платежа</td>
          <td>
            <el-dropdown @command="setType">
              <span class="el-dropdown-link">
                {{ payment.type_name }}<i class="el-icon-arrow-down el-icon--right" />
              </span>
              <el-dropdown-menu>
                <el-dropdown-item
                  v-for="item in receipType"
                  :key="item.id"
                  :command="item.id"
                >
                  {{ item.name }}
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </td>
          <td />
        </tr>
        <tr v-if="payment.type_depends">
          <td>Показания</td>
          <td>
            <div v-if="payment.depends.length > 0" class="flex">
              <div
                v-for="dep in payment.depends"
                :key="dep.id"
                class="flex br2 b--solid bw1 mh1 ph2 pv1"
                :class="{'b--dark-green': payment.instr_read['d' + dep.id] && payment.instr_read['d' + dep.id].value, 'b--dark-red': !payment.instr_read['d' + dep.id]}"
                @click="setMetering(dep)"
              >
                <div>
                  <div>
                    {{ dep.name[1] }}:
                  </div>
                  <div class="f7 gray">
                    sn: {{ dep.serial_number }}
                  </div>
                </div>
                <div v-if="payment.instr_read['d' + dep.id]">
                  {{ payment.instr_read['d' + dep.id].value }}
                </div>
              </div>
            </div>
            <div v-else class="red"> Приборы не найдены</div>
          </td>
          <td />
        </tr>
        <tr>
          <td>Примечание</td>
          <td>
            <div v-html="payment.description" />
          </td>
          <td>
            <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editDescription" />
          </td>
        </tr>
      </table>
      <div v-if="payment.error">
        <el-button type="success" size="mini" plain icon="el-icon-check" @click="confirmData">Подтвердить данные</el-button>
      </div>
      <span class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">Закрыть</el-button>
      </span>
    </el-dialog>
    <ChangeMetersData
      v-if="meteringShow"
      :description="payment.raw_data[7]"
      :data="payment.instr_read"
      @close="meteringClose"
      @saveData="saveMetering"
    />
  </div>
</template>

<script>
import { fetchPaymentInfo, updatePaymentInfo } from 'src/Modules/Bookkeeping/api/paymentApi.js'
import ChangeMetersData from 'src/Modules/Bookkeeping/components/ChangeMetersData/index.vue'
import { fetchReceiptTypeList } from 'src/Modules/Receipt/api/receiptAdminApi.js'
import UserSteadFind from 'src/Modules/Stead/components/UserSteadFind/index.vue'
import ShowTime from 'components/ShowTime/index.vue'

export default {
  components: {
    ChangeMetersData,
    UserSteadFind,
    ShowTime
  },
  props: {
    id: {
      type: [Number, String],
      default: ''
    }
  },
  data() {
    return {
      payment: {},
      findSteadShow: false,
      dialogVisible: true,
      meteringShow: false,
      showRaw: false,
      tempStead: null,
      receipType: []
    }
  },
  computed: {
    stead_error() {
      return this.payment.stead.number !== this.payment.raw_data[2]
    },
    type_error() {
      return this.payment.type === null
    }
  },
  mounted() {
    this.getPaymetData()
    this.getReceipt()
  },
  methods: {
    putStead(id) {
      this.$router.push('/admin/bookkeping/billing_balance_stead/' + id)
    },
    getPaymetData() {
      fetchPaymentInfo(this.id)
        .then(response => {
          if (response.data.status) {
            this.payment = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    saveNewStead() {
      this.findSteadShow = false
      this.payment.stead = this.tempStead
      this.payment.stead_id = this.tempStead.id
      this.saveData()
    },
    setStead(val) {
      this.tempStead = val
    },
    changeStead() {
      this.findSteadShow = !this.findSteadShow
    },
    close() {
      this.$emit('close')
    },
    meteringClose() {
      this.meteringShow = false
    },
    saveMetering(val) {
      this.payment.instr_read = val
      this.saveData()
    },
    setMetering(val) {
      this.$prompt(val.name, 'Укажите', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.payment.instr_read['d' + val.id] ? this.payment.instr_read['d' + val.id].value : ''
        // inputPattern: /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
      }).then(({ value }) => {
        const di = 'd' + val.id
        this.payment.instr_read = Object.assign({}, this.payment.instr_read, { [di]: { device: val.id, value } })
        this.saveData()
      }).catch(() => {

      })
      // this.meteringShow = true
    },
    confirmData() {
      this.$confirm('Установить что что все данные в платеже проверены?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        this.payment.error = false
        this.saveData()
      }).catch(() => {
      })
    },
    getReceipt() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receipType = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    setType(val) {
      this.payment.type = val
      this.payment.type_name = this.receipType.find(item => {
        return item.id === val
      }).name
      this.payment.type_depends = this.receipType.find(item => {
        return item.id === val
      }).depends === 2
      this.payment.depends = this.receipType.find(item => {
        return item.id === val
      })

      this.saveData()
    },
    editDescription() {
      this.$prompt('Добавить', 'Примечание', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.payment.description
      }).then(({ value }) => {
        this.payment.description = value
        this.saveData()
      }).catch(() => {
      })
    },
    saveData() {
      updatePaymentInfo(this.payment.id, this.payment)
        .then(response => {
          if (response.data.status) {
            if (!response.data.device) {
              this.$message.error('Ошибка при добавлении показаний!')
            }
            this.getPaymetData()
            // todo вернуть данные наверх!!!!
            // this. = response.data.data
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

.el-dropdown-link {
  cursor: pointer;
  color: #409EFF;
}

.el-icon-arrow-down {
  font-size: 12px;
}
</style>
