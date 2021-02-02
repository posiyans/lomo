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
            {{ invoice.price | formatPrice }}
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
import { fetchInvoiceInfo, updateInvoice } from '@/api/admin/bookkeping/invoice'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'

export default {
  components: {
  },
  props: {
    id: {
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
      fetchInvoiceInfo(this.id)
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
    close() {
      this.$emit('close')
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
    editDescription() {
      this.$prompt('Добавить', 'Примечание', {
        confirmButtonText: 'Сохранить',
        cancelButtonText: 'Отмена',
        inputValue: this.invoice.discription
      }).then(({ value }) => {
        this.invoice.discription = value
        this.saveData()
      }).catch(() => {
      })
    },
    saveData() {
      updateInvoice(this.invoice.id, this.invoice)
        .then(response => {
          if (response.data.status) {
            this.getInvoiceData()
            // this.close()
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
