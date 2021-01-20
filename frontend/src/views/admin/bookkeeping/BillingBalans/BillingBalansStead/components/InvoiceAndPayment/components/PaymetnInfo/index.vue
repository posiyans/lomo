<template>
  <div>
    <el-dialog
      title="Платеж"
      :visible.sync="dialogVisible"
      top="15px"
      @close="close"
    >
      <table border="1" class="do-not-carry">
        <tr class="bg-black-05">
          <th>Поле</th>
          <th>Значение</th>
          <th />
        </tr>
        <tr>
          <td>id</td>
          <td>{{ payment.id }}</td>
          <td />
        </tr>
        <tr>
          <td>Дата</td>
          <td>{{ payment.payment_date | moment('DD-MM-YYYY HH:mm') }}</td>
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
              <span v-else>
                {{ payment.stead.number }}
              </span>
            </div>
            <div v-if="findSteadShow" class="flex">
              <UserSteadFind :stead_id="payment.stead.id" @selectStead="setStead" />
              <el-button type="success" size="small" @click="saveNewStead">Ok</el-button>
            </div>
          </td>
          <td><el-button type="primary" size="mini" plain icon="el-icon-edit" @click="changeStead" /></td>
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
            {{ payment.type_depends }}
            <el-dropdown @command="setType">
              <span class="el-dropdown-link">
                {{ payment.type_name }}<i class="el-icon-arrow-down el-icon--right" />
              </span>
              <el-dropdown-menu slot="dropdown">
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
            <div class="flex">
              <div v-for="dep in payment.depends" :key="dep.id" class="b--dark-green br2 b--solid bw1 mh1 ph2 pv1" @click="setMetering(dep)">
                {{ dep.name[1] }}:
                <span v-if="payment.instr_read['d' + dep.id]">
                  {{ payment.instr_read['d' + dep.id].value }}
                </span>
              </div>
            </div>
          </td>
          <td />
        </tr>
        <tr>
          <td>Примечание</td>
          <td>
            <div v-html="payment.discription" />
          </td>
          <td>
            <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editDescription" />
          </td>
        </tr>
      </table>
      <div v-if="payment.error">
        <el-button type="success" size="mini" plain icon="el-icon-check" @click="confirmData">Подтвердить данные</el-button>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">Закрыть</el-button>
      </span>
    </el-dialog>
    <ChangeMetersData
      v-if="meteringShow"
      :discription="payment.raw_data[7]"
      :data="payment.instr_read"
      @close="meteringClose"
      @saveData="saveMetering"
    />
  </div>
</template>

<script>
import { updatePaymentInfo } from '@/api/admin/bookkeping/payment'
import ChangeMetersData from '@/components/Bookkeeping/ChangeMetersData'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'
import UserSteadFind from '@/components/UserSteadFind'

export default {
  components: {
    ChangeMetersData,
    UserSteadFind
  },
  props: {
    payment: {
      type: Object,
      default: () => ({})
    },
    showDialog: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
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
    this.getReceipt()
  },
  methods: {
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
        const di = `d` + val.id
        this.payment.instr_read = Object.assign({}, this.payment.instr_read, { [di]: { device: val.id, value: value }})
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
      this.payment.type_name = this.receipType.find(item => { return item.id === val }).name
      this.payment.type_depends = this.receipType.find(item => { return item.id === val }).depends === 2
      this.payment.depends = this.receipType.find(item => { return item.id === val })

      this.saveData()
    },
    editDescription() {
      this.$prompt('Добавить', 'Примечание', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.payment.discription
      }).then(({ value }) => {
        this.payment.discription = value
        this.saveData()
      }).catch(() => {
      })
    },
    saveData() {
      updatePaymentInfo(this.payment.id, this.payment)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
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
