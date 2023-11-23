<template>
  <div class="q-pa-md">
    <div class="page-title">Участки</div>
    <div class="row items-center q-col-gutter-md q-pb-sm">
      <div>
        <el-input v-model="listQuery.title" placeholder="Найти" style="width: 200px;" class="filter-container__item" @keyup.enter="handleFilter" />

      </div>
      <div>

        <el-button class="filter-container__item" type="primary" @click="handleFilter">
          Показать
        </el-button>
      </div>
      <div>

        <el-button :loading="downloadLoading" class="filter-container__item" type="primary" @click="handleDownload">
          XLSX
        </el-button>
      </div>
    </div>

    <el-table
      :key="tableKey"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column label="№" align="center" width="80px">
        <template #default="{row}">
          <span>{{ row.number }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Размер, кв.м" align="center" width="80px">
        <template #default="{row}">
          <span>{{ row.size }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="desktop" label="Собственник" width="350px">
        <template #default="{row}">
          <div>
            <div v-for="item in row.owners" :key="item.uid">
              {{ item.name }}
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column v-if="desktop" label="Баланс" align="center" width="120px">
        <template #default="{row}">
          <div :class="{'dark-red': row.balans < 0}">
            {{ row.balans }}
          </div>
        </template>
      </el-table-column>
      <!--      <el-table-column v-if="desktop" label="Телефон" align="center" width="150px">-->
      <!--        <template slot-scope="{row}">-->
      <!--          <span>{{ row.user.phone }}</span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
      <!--      <el-table-column v-if="desktop" label="Email" align="center" width="150px">-->
      <!--        <template slot-scope="{row}">-->
      <!--          <span>{{ row.user.email }}</span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->
      <el-table-column label="Actions" align="center" width="270px" class-name="small-padding fixed-width">
        <template #default="{ row }">
          <el-button type="success" size="small" @click="AddNoteShow(row)">
            Прим.
          </el-button>
          <el-button type="primary" size="small" @click="handleUpdate(row)">
            Инфо.
          </el-button>
          <el-button type="primary" size="small" title="Квитанции" @click="getReceipt(row)">
            Квит.
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />

    <el-dialog title="Добавить примечание" v-model="dialogNoteVisible" width="80%">
      <el-form :label-position="labelPosition" label-width="150px">
        <el-form-item label="Примечание">
          <el-input v-model="newNote" :autosize="{ minRows: 2}" type="textarea" placeholder="Место для заметок" />
        </el-form-item>
      </el-form>
      <div class="dialog-footer">
        <el-button @click="dialogNoteVisible = false">
          Закрыть
        </el-button>
        <el-button type="primary" @click="addNote">
          Добавить
        </el-button>
      </div>
    </el-dialog>
    <el-dialog title="Скачать квитанцию" v-model="dialogReceipShow" :width="desktop ? '500px' : '100%'">
      <div class="pl4-ns">
        <div class="pb4">Собственник: <span v-if="receiptData.descriptions"><b>{{ receiptData.descriptions.fio }}</b></span></div>
        <el-form :label-position="labelPosition" label-width="100px">
          <el-form-item label="Оплата">
            <el-select
              v-model="receiptType"
              placeholder="Select"
              @change="getRate()"
            >
              <el-option
                v-for="item in options"
                :key="item.key"
                :label="item.label"
                :value="item.key"
              />
            </el-select>
          </el-form-item>
        </el-form>
        <div v-for="i in rate" :key="i.name">
          <div class="pb1">
            {{ i.name }} - {{ i.rate.description }}
            <span v-if="i.rate.ratio_a > 0">
              * {{ receiptData.size / 100 }} = {{ (receiptData.size / 100 * i.rate.ratio_a).toFixed(2) }} руб.
            </span>
          </div>
        </div>
        <div class="pt4"><b>Итого: </b>{{ totalFilter(receiptData.size, rate) }} руб.</div>
        <div class="dialog-footer tr mt4 mr4-ns ">
          <el-button @click="dialogReceipShow = false">
            Закрыть
          </el-button>
          <el-button type="primary" @click="receive">
            Скачать
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchSteadList, fetchSteadListInXlsx, updateStead } from 'src/Modules/Stead/api/steadAdminApi.js'
// import { fetchSteadList, updateStead } from 'src/Modules/Stead/api/steadAdminApi.js'
import { fetchList } from 'src/Modules/Rates/api/rates.js'
import { getReceipt } from 'src/Modules/Receipt/api/receiptAdminApi.js'
// import { saveAs } from 'file-saver'
import LoadMore from 'src/components/LoadMore/index.vue'
import { exportFile } from 'quasar'

export default {
  name: 'AdminSteadList',
  components: { LoadMore },
  data() {
    return {
      options: [
        // { label: 'Коммунальные', key: 1 },
        { label: 'Взносы', key: 2 }
      ],
      receiptType: 2,
      rate: [],
      tableKey: 0,
      list: [],
      total: 0,
      key: 1,
      listLoading: true,
      func: fetchSteadList,
      listQuery: {
        page: 1,
        limit: 20,
        title: undefined
      },
      temp: {
        user: {},
        descriptions: {},
        number: '',
        size: ''
      },
      dialogNoteVisible: false,
      dialogReceipShow: false,
      receiptData: {},
      newNote: '',
      rules: {
        number: [{ required: true, message: 'Обязательное поле', trigger: 'change' }],
        size: [{ required: true, message: 'Обязательное поле', trigger: 'change' }]
      },
      downloadLoading: false
    }
  },
  computed: {
    desktop() {
      return this.$q.platform.is.desktop
    },
    labelPosition() {
      return this.desktop ? 'left' : 'top'
    },
    editor() {
      // if (this.$store.getters.user.allPermissions.includes('edit-stead')) {
      //   return true
      // }
      return false
    }
  },
  mounted() {
    // this.getList()
  },
  methods: {
    totalFilter(size, rate) {
      let sum = 0
      rate.forEach(i => {
        sum = Number(sum) + Number(i.rate.ratio_a) * Number(size) / 100 + Number(i.rate.ratio_b)
      })
      return sum
    },
    setList(val) {
      this.list = val
    },
    receive() {
      const data = {
        receipt: this.receiptType,
        stead: this.receiptData.id
      }
      getReceipt(data).then(response => {
        exportFile('Квитанция_' + this.receiptData.number + '.pdf', new Blob([response.data], {
          type: response.data.type
        }))
        this.dialogReceipShow = false
      })
    },
    getRate() {
      fetchList({ type: this.receiptType }).then(response => {
        this.rate = response.data.data
        this.loading = false
      })
    },
    getReceipt(row) {
      this.getRate()
      this.receiptData = row
      this.dialogReceipShow = true
    },
    handleFilter() {
      this.listQuery.page = 1
      this.key++
    },
    AddNoteShow(row) {
      this.note_id = row.id
      this.dialogNoteVisible = true
    },
    addNote() {
      const data = {
        type: 'addNote',
        note: this.newNote
      }
      updateStead(this.note_id, data)
        .then(response => {
          if (response.data.status) {
            this.getList()
            this.$message({
              message: 'Примечание сохранено',
              type: 'success'
            })
          } else {
            this.$message({
              message: 'Ошибка при сохранении',
              type: 'warning'
            })
          }
          this.dialogNoteVisible = false
        })
    },

    handleUpdate(row) {
      // this.temp = row // copy obj
      this.$router.push('/admin/bookkeping/billing_balance_stead/' + row.id)
    },
    updateData() {
      if (this.editor) {
        this.$refs.dataForm.validate((valid) => {
          if (valid) {
            const data = {
              type: 'update',
              model: this.temp
            }
            updateStead(this.temp.id, data)
              .then(response => {
                this.dialogFormVisible = false
                this.$notify({
                  title: 'Success',
                  message: 'Update Successfully',
                  type: 'success',
                  duration: 2000
                })
              })
          }
        })
      }
    },
    handleDownload() {
      this.$message('Не работает.')
      fetchSteadListInXlsx(this.listQuery).then(response => {
        exportFile('Список.xlsx', new Blob([response.data]))
        this.$message('Файл успешно скачан.')
      })
    }
  }
}
</script>

<style scoped>
.grey {
  color: #d6d6d6;
}
</style>
