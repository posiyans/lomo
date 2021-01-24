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
          <td>{{ invoice.id }}</td>
          <td />
        </tr>
        <tr>
          <td>Дата</td>
          <td>{{ invoice.created_at | moment('DD-MM-YYYY HH:mm') }}</td>
          <td />
        </tr>
        <tr class="black">
          <td>Участок</td>
          <td>
            <span>
              {{ invoice.stead_number }}
            </span>
          </td>
          <td />
        </tr>
        <tr>
          <td>Сумма</td>
          <td>
            {{ invoice.price }}
          </td>
          <td />
        </tr>
        <tr>
          <td>Назначение</td>
          <td>
            {{ invoice.title }}
          </td>
          <td />
        </tr>
        <tr class="black">
          <td>Тип платежа</td>
          <td>
            <span>
              {{ invoice.type_name }}
            </span>
          </td>
          <td />
        </tr>
        <tr>
          <td>Примечание</td>
          <td>
            <div v-html="invoice.discription" />
          </td>
          <td>
            <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editDescription" />
          </td>
        </tr>
      </table>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">Закрыть</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { fetchInvoiceInfo } from '@/api/admin/bookkeping/invoice'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'

export default {
  components: {
  },
  props: {
    invoiceId: {
      type: [Number, String],
      default: ''
    }
  },
  data() {
    return {
      invoice: {},
      findSteadShow: false,
      dialogVisible: true,
      meteringShow: false,
      showRaw: false,
      tempStead: null,
      receipType: []
    }
  },
  computed: {
    // stead_error() {
    //   return this.payment.stead.number !== this.payment.raw_data[2]
    // },
    // type_error() {
    //   return this.payment.type === null
    // }
  },
  mounted() {
    this.getInvoiceData()
    this.getReceipt()
  },
  methods: {
    getInvoiceData() {
      fetchInvoiceInfo(this.invoice_id)
        .then(response => {
          if (response.data.status) {
            this.invoice = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    // saveNewStead() {
    //   this.findSteadShow = false
    //   this.invoice.stead = this.tempStead
    //   this.invoice.stead_id = this.tempStead.id
    //   this.saveData()
    // },
    // setStead(val) {
    //   this.tempStead = val
    // },
    // changeStead() {
    //   this.findSteadShow = !this.findSteadShow
    // },
    close() {
      this.$emit('close')
    },
    // meteringClose() {
    //   this.meteringShow = false
    // },
    // saveMetering(val) {
    //   this.payment.instr_read = val
    //   this.saveData()
    // },
    // setMetering(val) {
    //   this.$prompt(val.name, 'Укажите', {
    //     confirmButtonText: 'Сохранить',
    //     cancelButtonText: 'Отмена',
    //     inputValue: this.payment.instr_read['d' + val.id] ? this.payment.instr_read['d' + val.id].value : ''
    //     // inputPattern: /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
    //   }).then(({ value }) => {
    //     const di = `d` + val.id
    //     this.payment.instr_read = Object.assign({}, this.payment.instr_read, { [di]: { device: val.id, value: value }})
    //     this.saveData()
    //   }).catch(() => {
    //
    //   })
    //   // this.meteringShow = true
    // },
    // confirmData() {
    //   this.$confirm('Установить что что все данные в платеже проверены?', 'Внимание!', {
    //     confirmButtonText: 'Да',
    //     cancelButtonText: 'Нет',
    //     type: 'warning'
    //   }).then(() => {
    //     this.payment.error = false
    //     this.saveData()
    //   }).catch(() => {
    //   })
    // },
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
      // updatePaymentInfo(this.payment.id, this.payment)
      //   .then(response => {
      //     if (response.data.status) {
      //       if (!response.data.device) {
      //         this.$message.error('Ошибка при добавлении показаний!')
      //       }
      //       this.getPaymetData()
      //       // todo вернуть данные наверх!!!!
      //       // this. = response.data.data
      //     } else {
      //       if (response.data.data) {
      //         this.$message.error(response.data.data)
      //       }
      //     }
      //   })
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
