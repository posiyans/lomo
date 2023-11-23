<template>
  <div class="app-container">
    <div class="page-title">Добавить выписку из банка</div>
    <div>
      <el-upload
        ref="upload"
        class="upload-demo"
        action="#"
        multiple
        :limit="100"
        :on-exceed="handleExceed"
        :auto-upload="false"
        :on-change="onFileChange"
      >
        <el-button size="small" type="primary">Выбрать файлы</el-button>
        <div class="el-upload__tip">txt,cvs,xlsx выписки из банка, до 100 файлов</div>
      </el-upload>

    </div>
    <div class="mt2">
      Загруженно {{ fileCount }} файлов
    </div>
    <div v-if="data_ok.length > 0">
      <table :key="key">
        <tr>
          <th v-for="h in name" :key="h">{{ h }}</th>
          <th v-if="addCol">
            Участок
          </th>
          <th v-if="addCol">
            Платеж
          </th>
          <th v-if="addCol" />
        </tr>
        <tr v-for="(line, j) in data_ok" :key="'l'+ j">
          <td v-for="(n, i) in name" :key="i">
            <span v-if="i === 0">
              {{ ++j }}
            </span>
            <span v-if="i > 0">
              {{ line[i - 1] }}
            </span>
          </td>
          <td v-if="addCol">
            <div v-if="line.raw_data && line.raw_data[2] !== line.stead.number" class="dark-red" @click="editStead(line)">
              {{ line.raw_data[2] }} -> {{ line.stead.number }}
            </div>
          </td>
          <td v-if="addCol">
            <div v-if="line.dubl">
              <el-tag type="danger" effect="dark">
                Повтор
              </el-tag>
            </div>
            <div v-else>
              <el-dropdown @click="dropClick(line)" @command="setType">
                <span class="el-dropdown-link">
                  <span v-if="typeName[line.type]">
                    {{ typeName[line.type].name }}
                  </span>
                  <span v-else class="dark-red">-------!</span>
                  <i class="el-icon-arrow-down el-icon--right" />
                </span>
                <el-dropdown-menu>
                  <el-dropdown-item
                    v-for="item in typeList"
                    :key="item.id"
                    :command="item.id"
                  >
                    {{ item.name }}
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </div>
          </td>
          <td v-if="addCol">
            <span v-if="!line.dubl && line.error">
              <el-button type="success" @click="paymentOk(line)">Подтвердить</el-button>
            </span>
          </td>
        </tr>
      </table>
      <div class="pa3">
        <el-button type="danger" plain @click="close">Отмена</el-button>
        <el-button v-loading="loading" type="success" @click="upload">Загрузить</el-button>
      </div>
    </div>
    <div v-if="dialogSteadFormVisible">
      <el-dialog
        title="Укажите участок"
        v-model="dialogSteadFormVisible"
      >
        <UserSteadFind :read_only="false" :stead_id="editRow.stead.id" @selectStead="setStead" />
        <span class="dialog-footer">
          <el-button @click="dialogSteadFormVisible = false">Отмена</el-button>
          <el-button type="primary" @click="confirmStead">Ок</el-button>
        </span>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import UserSteadFind from 'src/Modules/Stead/components/UserSteadFind/index.vue'
import readFile from './js/readFile'
import { uploadDischarge } from 'src/Modules/Bookkeeping/api/bankAdminApi.js'
import { fetchReceiptTypeList } from 'src/Modules/Receipt/api/receiptAdminApi.js'
import { updatePaymentInfo } from 'src/Modules/Bookkeeping/api/paymentAdminApi.js'

export default {
  components: {
    UserSteadFind
  },
  data() {
    return {
      typeName: {},
      typeList: [],
      editRow: {},
      tempStead: {},
      dialogSteadFormVisible: false,
      editType: '',
      fileCount: 0,
      key: 1,
      row: 0,
      originalData: '',
      data: '',
      data_ok: [],
      data_error: [],
      summa: 0,
      loading: false,
      addCol: false,
      name: [
        '№',
        'дата',
        'Сумма',
        'Участок',
        'ФИО',
        'Назначение'
      ]
    }
  },
  computed: {
    item_summa() {
      let l = 0
      this.data_ok.forEach(i => {
        const s = i[9].replace(',', '.')
        l += +s
      })
      return l
    },
    is_error() {
      return this.summa.toFixed(2) !== this.item_summa.toFixed(2) || this.data
    },
    url() {
      return process.env.VUE_APP_BASE_API + '/api/v1/admin/billing/bank-reestr/upload'
    }
  },
  created() {
    this.getTypeList()
  },
  methods: {
    paymentOk(row) {
      row.error = false
      this.savePayment(row)
    },
    confirmStead() {
      this.dialogSteadFormVisible = false
      if (this.tempStead && this.tempStead.id) {
        this.editRow.stead_id = this.tempStead.id
        this.savePayment(this.editRow)
        if (this.editRow.type) {
          this.editRow.error = false
        }
      }
    },
    setStead(stead) {
      this.tempStead = stead
    },
    editStead(row) {
      if (!row.dubl) {
        this.tempStead = false
        this.editRow = row
        this.dialogSteadFormVisible = true
      }
    },
    setType(val) {
      this.editType.type = val
      this.savePayment(this.editType)
    },
    dropClick(row) {
      this.editType = row
    },
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.typeList = response.data.data
            response.data.data.forEach(item => {
              this.typeName = Object.assign({}, this.typeName, { [item.id]: item })
            })
            // console.log(this.typeName)
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
    handleExceed(files, fileList) {
      this.$message.warning('Ограничение - 100 файлов')
    },
    savePayment(data) {
      updatePaymentInfo(data.id, data)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            const nm = Object.assign({}, data, response.data.data)
            this.data_ok.splice(this.data_ok.indexOf(data), 1, nm)
            this.key++
            // this. = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    onFileChange(e) {
      this.addCol = false
      this.loading = false
      this.data = ''
      this.row = 0
      this.summa = 0
      this.data_ok = []
      const files = this.$refs.upload.uploadFiles
      if (!files.length) return
      // console.log(files)
      // let c = 0
      this.data_ok = []
      this.fileCount = 0
      files.forEach(i => {
        readFile(i.raw).then(text => {
          text.forEach(i => {
            this.data_ok.push(i)
          })
          this.fileCount++
        })
      })
      this.loading = false
      this.$refs.upload.clearFiles()
    },
    sendData() {
      let time = 10
      this.data_ok.forEach(item => {
        setTimeout(() => {
          uploadDischarge({ item })
            .then(response => {
              if (response.data.status) {
                this.loading = false
                const nm = Object.assign({}, item, response.data.data)
                this.data_ok.splice(this.data_ok.indexOf(item), 1, nm)
              }
              this.key++
            })
        }, time)
        time += 100
      })
      setTimeout(() => {
        this.key++
      }, time)
    },
    upload() {
      this.$confirm('Загрузить выписку в систему?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        this.addCol = true
        this.sendData()
      }).catch(() => {
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
  border: 1px solid #000000;
  padding: 0 10px;
  text-align: center;
}
</style>
