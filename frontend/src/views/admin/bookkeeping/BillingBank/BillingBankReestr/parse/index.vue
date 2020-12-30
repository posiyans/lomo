<template>
  <div>
    <div>
      <el-upload
        ref="upload"
        class="upload-demo"
        action="#"
        multiple
        :limit="10"
        :on-exceed="handleExceed"
        :auto-upload="false"
      >
        <el-button slot="trigger" size="small" type="primary">Выбрать файлы</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="onFileChange">Загрузить данные</el-button>
        <div slot="tip" class="el-upload__tip">txt или cvs выписки из банка</div>
      </el-upload>

    </div>
    <!--    <input type="file" @change="onFileChange" class="inputFile" />-->
    <div v-if="is_error" class="dark-red">
      <div class="f3 pa3">Ошибка разбора файла</div>
      <div v-if="summa != item_summa" class="ba pa3">
        Не совпадает сумма строк и контрольных точек
        <div class="mt1">
          Сумма по строкам
          <span class="dark-blue">
            {{ item_summa.toFixed(2) }}
          </span>
        </div>
        <div class="mt1">
          Котрольная сумма
          <span class="dark-blue">
            {{ summa.toFixed(2) }}
          </span>
        </div>
      </div>
      <div v-if="data" class="ba">
        <div class="pa3 f4">
          Ошибка разбора строк
        </div>
        <el-input
          v-model="data"
          type="textarea"
          placeholder=""
          :rows="row"
        />
        <div class="pa3">
          <el-button type="primary" @click="parseData">Обновить</el-button>

        </div>
      </div>
    </div>
    <table>
      <tr>
        <th v-for="h in name">{{ h }}</th>
      </tr>
      <tr v-for="(line, j) in data_ok">
        <td>{{ ++j }}</td>
        <td v-for="i in line">{{ i }}</td>
      </tr>
    </table>
    <div class="pa3">
      <el-button type="success" @click="upload">Загрузить</el-button>
    </div>
  </div>
</template>

<script>
import readFile from './readFile'
import { uploadDischarge } from '@/api/admin/bookkeping/bank'
export default {
  data() {
    return {
      row: 0,
      originalData: '',
      data: '',
      data_ok: [],
      data_error: [],
      summa: 0,
      name: [
        '№',
        'дата',
        'время',
        '-',
        '-',
        '-',
        'участок',
        'ФИО',
        'Назначение',
        'Сумма',
        'Сумма',
        '-',
        '-'
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
    handleExceed(files, fileList) {
      this.$message.warning(`Ограничение - 10 файлов`)
    },
    onFileChange(e) {
      this.data = ''
      this.row = 0
      this.summa = 0
      this.data_ok = []
      const files = this.$refs.upload.uploadFiles
      if (!files.length) return
      let c = 0
      files.forEach(i => {
        readFile(i.raw, text => {
          this.data += text
          c++
          if (c === files.length) {
            this.parseData()
          }
        })
      })
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
        uploadDischarge({ data: this.data_ok })
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
