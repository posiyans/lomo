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
        <el-button slot="trigger" size="small" type="primary">Выбрать файлы</el-button>
        <div slot="tip" class="el-upload__tip">txt,cvs,xlsx выписки из банка, до 100 файлов</div>
      </el-upload>

    </div>
    <div class="mt2">
      Загруженно {{ fileCount }} файлов
    </div>
    <div v-if="data_ok.length > 0">
      <table>
        <tr>
          <th v-for="h in name" :key="h">{{ h }}</th>
        </tr>
        <tr v-for="(line, j) in data_ok" :key="line">
          <td>{{ ++j }}</td>
          <td v-for="i in line" :key="i">{{ i }}</td>
        </tr>
      </table>
      <div class="pa3">
        <el-button type="danger" plain @click="close">Отмена</el-button>
        <el-button v-loading="loading" type="success" @click="upload">Загрузить</el-button>
      </div>
    </div>
  </div>
</template>

<script>
import readFile from './readFile'
import { uploadDischarge } from '@/api/admin/bookkeping/bank'
// import UploadExcel from '@/components/UploadExcel'

export default {
  components: {
    // UploadExcel
  },
  data() {
    return {
      fileCount: 0,
      key: 1,
      row: 0,
      originalData: '',
      data: '',
      data_ok: [],
      data_error: [],
      summa: 0,
      loading: false,
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
  methods: {
    close() {
      this.$emit('close')
    },
    handleExceed(files, fileList) {
      this.$message.warning(`Ограничение - 100 файлов`)
    },
    onFileChange(e) {
      this.loading = false
      this.data = ''
      this.row = 0
      this.summa = 0
      this.data_ok = []
      const files = this.$refs.upload.uploadFiles
      if (!files.length) return
      console.log(files)
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
    parseData() {
      this.$refs.upload.clearFiles()
      let temp = ''
      this.row = 2
      this.data.split(/\r?\n/).forEach(i => {
        if (i.trim().length > 0) {
          const line = i.split(';')
          if (line.length === 12 && (line[0].match(/-/g) || []).length === 2 && (line[1].match(/-/g) || []).length === 2) {
            const t1 = line[0] + ' ' + line[1]
            const t2 = this.$moment(line[0] + ' ' + line[1], 'DD-MM-YYYY HH-mm-ss', false).format('DD-MM-YYYY HH-mm-ss')
            if (t1 === t2) {
              this.data_ok.push(line)
            } else {
              this.row++
              temp += i + '\r\n'
            }
          } else {
            if (line.length === 6 && i[0] === '=') {
              this.summa += +line[1].replace(',', '.')
            } else {
              this.row++
              temp += i + '\r\n'
            }
          }
        }
      })
      this.data = temp
    },
    upload() {
      this.$confirm('Загрузить выписку в систему?', 'Внимание!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Нет',
        type: 'warning'
      }).then(() => {
        this.loading = true
        uploadDischarge({ data: this.data_ok })
          .then(response => {
            if (response.data.status) {
              this.loading = false
              this.$emit('showPayment', response.data.data)
              // this. = response.data.data
            } else if (response.data.data) {
              this.$message.error(response.data.data)
            }
          })
      }).catch(() => {})
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
