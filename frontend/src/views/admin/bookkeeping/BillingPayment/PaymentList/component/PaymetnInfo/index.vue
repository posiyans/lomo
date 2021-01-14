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
            <span v-if="stead_error">
              {{ payment.raw_data[5] }} -->
              {{ payment.stead.number }}
            </span>
            <span v-else>
              {{ payment.stead.number }}
            </span>
          </td>
          <td><el-button type="primary" size="mini" plain icon="el-icon-edit" /></td>
        </tr>
        <tr>
          <td>ФИО</td>
          <td>
            {{ payment.raw_data[6] }}
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
          <td>{{ payment.raw_data[7] }}</td>
          <td />
        </tr>
        <tr class="black" :class="{'bg-washed-red': type_error}">
          <td>Тип платежа</td>
          <td>
            <el-tag v-if="payment.type == 1" type="danger">Электричество</el-tag>
            <el-tag v-if="payment.type == 2" type="success">Взносы</el-tag>
          </td>
          <td>
            <el-popconfirm
              confirm-button-text="Взносы"
              confirm-button-type="success"
              cancel-button-text="Электроэнергия"
              cancel-button-type="danger"
              icon="el-icon-info"
              icon-color="red"
              title="Сменть тип платежа?"
              @onConfirm="setType(2)"
              @onCancel="setType(1)"
            >
              <el-button slot="reference" type="primary" size="mini" plain icon="el-icon-edit" />
            </el-popconfirm>
          </td>
        </tr>
        <tr v-if="payment.type == 1">
          <td>Показания эл.эн.</td>
          <td>
            <span v-for="i in payment.instr_read" :key="i.value" class="pl2">
              <el-tag v-if="i.value" type="success">{{ i.device }}-{{ i.value }}</el-tag>
            </span>
          </td>
          <td>
            <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="setMetering" />
          </td>
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
        <tr>
          <td @click="showRaw = !showRaw">
            <span class="dark-blue pointer">
              Выписка
            </span>
          </td>
          <td>
            <div v-if="showRaw">
              <pre>
                {{ payment.raw_data }}
              </pre>
            </div>
          </td>
          <td />
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

export default {
  components: {
    ChangeMetersData
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
      dialogVisible: true,
      meteringShow: false,
      showRaw: false
    }
  },
  computed: {
    stead_error() {
      return this.payment.stead.number !== this.payment.raw_data[5]
    },
    type_error() {
      return this.payment.type === null
    }
  },
  methods: {
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
    setMetering() {
      this.meteringShow = true
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
    setType(val) {
      this.payment.type = val
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
</style>
