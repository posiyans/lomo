<template>
  <div class="app-container">
    <div class="admin-parge-title">Квитанции</div>
    <div class="filter-container">
      <el-select
        v-model="listQuery.stead"
        filterable
        remote
        reserve-keyword
        placeholder="Участок"
        no-data-text="Данный номер не найден"
        :remote-method="findStead"
        :loading="loading"
        class="filter-container__item"
        style="width: 100px;"
        @click="showReestr"
      >
        <el-option
          v-for="item in steadsList"
          :key="item.id"
          :label="item.number"
          :value="item.id"
        />
      </el-select>
      <el-select
        v-model="listQuery.type"
        placeholder="Тип"
        clearable
        class="filter-container__item"
        @change="showReestr"
      >
        <el-option v-for="item in receiptTypes" :key="item.id" :label="item.name" :value="item.id" />
      </el-select>
      <el-select
        v-model="listQuery.is_playment"
        placeholder="Оплата"
        clearable
        class="filter-container__item"
        @change="showReestr"
      >
        <el-option label="только оплаченные" value="1" />
        <el-option label="только неоплаченные" value="2" />
      </el-select>
      <el-button v-waves class="filter-container__item" type="primary" icon="el-icon-search" @click="showReestr">
        Показать
      </el-button>
      <el-button v-waves class="filter-container__item" type="success" icon="el-icon-download" @click="downloadDialogVisible = !downloadDialogVisible">
        Скачать
      </el-button>
      <el-checkbox v-model="listQuery.reestr" class="filter-container__item">Включить реест в файл</el-checkbox>
      <el-checkbox v-model="listQuery.fio" class="filter-container__item">Включить ФИО в файл</el-checkbox>
    </div>
    <ShowTable v-if="list.length > 0" v-loading="loading" :list="list" />
    <LoadMore :key="key" :list-query="listQuery" :func="func" @setList="setList" />
    <el-dialog
      title="Внимание"
      :visible.sync="downloadDialogVisible"
      width="30%"
      center
    >
      <div>Скачать текущую страницу или весь диапазон??</div>
      <div class="pt3">
        При загрузке большого диапозона, создание файла может занять некоторое<br> временя,  так что наберитесь терпения!
      </div>

      <span slot="footer" class="dialog-footer">
        <el-button style="margin-right: 20px;" @click="downloadDialogVisible = false">Отмена</el-button>
        <el-button type="primary" style="margin-right: 20px;" @click="downloadReestr(true)">Страницу</el-button>
        <el-button type="danger" @click="downloadReestr(false)">Все</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { getSteadsList } from '@/api/user/stead.js'
import { getReceiptList, getReceiptForSteadList } from '@/api/admin/receipt.js'
import ShowTable from './ShowTable'
import waves from '@/directive/waves'
import { saveAs } from 'file-saver'
import { fetchReceiptTypeList } from '@/api/admin/setting/receipt'
import LoadMore from '@/components/LoadMore'

export default {
  components: {
    ShowTable, LoadMore
  },
  directives: { waves },
  data() {
    return {
      key: 1,
      func: getReceiptList,
      loading: false,
      stead_min: null,
      stead_max: null,
      steadsList: [],
      steadsListMax: [],
      downloadDialogVisible: false,
      total: 0,
      list: [],
      receiptTypes: [],
      listQuery: {
        stead: '',
        is_playment: '2',
        type: '',
        page: 1,
        limit: 20
      },
      reestrType: [
        {
          key: 0,
          label: 'Не печатать реестр'
        },
        {
          key: 1,
          label: 'Печатать реестр'
        }
      ]
    }
  },
  created() {
    this.getTypeList()
    this.findStead()
  },
  mounted() {
    // this.showReestr()
  },
  methods: {
    getTypeList() {
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.receiptTypes = []
            response.data.data.forEach(item => {
              // if (!item.auto_invoice) {
              this.receiptTypes.push(item)
              // }
            })
            if (this.receiptTypes.length === 1) {
              this.listQuery.type = this.receiptTypes[0].id
            }
          } else if (response.data.data) {
            this.$message.error(response.data.data)
          }
        })
    },
    downloadReestr(padding = true) {
      this.$message('Запрос отправлен ожидайте файл!')
      const data = this.listQuery
      data.padding = padding
      this.downloadDialogVisible = false
      getReceiptForSteadList(data).then(response => {
        saveAs(new Blob([response.data], {
          type: response.data.type
        }), 'Квитанция.pdf')
        this.$message('Файл успешно скачан.')
      })
    },
    getStead() {
      getSteadsList()
        .then(response => {
          this.steadsList = response.data
        })
    },
    setList(val) {
      this.list = val
    },
    showReestr() {
      this.listQuery.page = 1
      this.key++
      // this.loading = true
      // getReceiptList(this.listQuery).then(response => {
      //   // if (response.data.status) {
      //   this.list = response.data.data
      //   this.total = response.data.total
      //   this.loading = false
      //   // }
      // })
    },
    findStead(query = '') {
      const data = {
        query: query
      }
      getSteadsList(data)
        .then(response => {
          this.steadsList = response.data
        })
    }
  }

}
</script>

<style scoped>

</style>
