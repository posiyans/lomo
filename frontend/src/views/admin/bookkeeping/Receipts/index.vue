<template>
  <div class="app-container">
    <div class="admin-parge-title">Квитанции</div>
    <div class="filter-container">
      <span class="filter-container__item">
        Участки c

      </span>
      <el-select
        v-model="listQuery.stead_min"
        filterable
        remote
        reserve-keyword
        placeholder="Введите номер участка"
        no-data-text="Данный номер не найден"
        :remote-method="findSteadMin"
        :loading="loading"
        class="filter-container__item"
      >
        <el-option
          v-for="item in steadsListMin"
          :key="item.id"
          :label="item.number"
          :value="item.id"
        />
      </el-select>
      <span class="filter-container__item">
        по
      </span>
      <el-select
        v-model="listQuery.stead_max"
        filterable
        remote
        reserve-keyword
        placeholder="Введите номер участка"
        no-data-text="Данный номер не найден"
        :remote-method="findSteadMax"
        class="filter-container__item"
        :loading="loading"
      >
        <el-option
          v-for="item in steadsListMax"
          :key="item.id"
          :label="item.number"
          :value="item.id"
        />
      </el-select>
      <el-button v-waves class="filter-container__item" type="primary" icon="el-icon-search" @click="showReestr">
        Показать
      </el-button>
      <el-button v-waves class="filter-container__item" type="success" icon="el-icon-download" @click="downloadDialogVisible = !downloadDialogVisible">
        Скачать
      </el-button>
      <el-checkbox v-model="listQuery.reestr" class="filter-container__item">Включить реест в фаил</el-checkbox>
      <el-checkbox v-model="listQuery.fio" class="filter-container__item">Включить ФИО в фаил</el-checkbox>
    </div>
    <ShowTable v-if="list.length > 0" v-loading="loading" :list="list" />
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="showReestr" />
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
import { getReestrForSteadList, getReceiptForSteadList } from '@/api/admin/receipt.js'
import ShowTable from './ShowTable'
import waves from '@/directive/waves'
import Pagination from '@/components/Pagination/index'
import { saveAs } from 'file-saver'

export default {
  components: {
    ShowTable, Pagination
  },
  directives: { waves },
  data() {
    return {
      loading: false,
      stead_min: null,
      stead_max: null,
      steadsListMin: [],
      steadsListMax: [],
      downloadDialogVisible: false,
      total: 0,
      list: [],
      listQuery: {
        reestr: true,
        fio: false,
        page: 1,
        limit: 20,
        stead_min: null,
        stead_max: null
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
  mounted() {
    this.getStead()
  },
  methods: {
    downloadReestr(padding = true) {
      this.showReestr()
      this.$message('Запрос отправлен ожидайте файл!')
      const data = this.listQuery
      data.padding = padding
      this.downloadDialogVisible = false
      getReceiptForSteadList(data).then(response => {
        saveAs(new Blob([response.data], {
          type: response.data.type
        }), 'Квитанция.pdf')
        this.$message('Фаил успешно скачен.')
      })
    },
    getStead() {
      getSteadsList()
        .then(response => {
          this.steadsListMin = response.data
          this.steadsListMax = response.data
        })
    },
    showReestr() {
      this.loading = true
      getReestrForSteadList(this.listQuery).then(response => {
        if (response.data.status) {
          this.list = response.data.data
          this.total = response.data.total
          this.loading = false
        }
      })
    },
    findSteadMin(query) {
      const data = {
        query: query
      }
      getSteadsList(data)
        .then(response => {
          this.steadsListMin = response.data
        })
    },
    findSteadMax(query) {
      const data = {
        query: query
      }
      getSteadsList(data)
        .then(response => {
          this.steadsListMax = response.data
        })
    }
  }

}
</script>

<style scoped>

</style>
