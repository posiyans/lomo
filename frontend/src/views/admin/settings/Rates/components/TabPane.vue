<template>
  <div>
    <div class="filter-container">
      <el-button v-waves class="filter-item" size="small" type="success" plain icon="el-icon-setting" @click="showSettings =! showSettings">
        Настройка
      </el-button>
      <el-button v-waves class="filter-item" size="small" type="success" plain icon="el-icon-circle-plus-outline" @click="addRateType">
        Добавить
      </el-button>
    </div>
    <EditSetting v-if="showSettings" :type="type" />
    <el-table :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column
        v-loading="loading"
        align="center"
        label="ID"
        width="65"
        element-loading-text="Пожалуйста, дайте мне немного времени！"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="300px" label="Название платежа">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
          <el-tag>{{ row.discription}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Readings" width="95">
        <template slot-scope="scope">
          <span>{{ scope.row.pageviews }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="Тариф">
        <template slot-scope="{row}">
          <span>{{ row.rate.discription }}</span>
        </template>
      </el-table-column>
      <el-table-column class-name="status-col" label="Status" width="140">
        <template slot-scope="{row}">
          <el-tag :type="row.enable | statusFilter">
            {{ row.enable ? 'Действующий' : 'Не действующий' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column class-name="status-col" label="Действие" width="140">
        <template slot-scope="{row}">
          <el-button type="primary" @click="editRating(row)">
            Изменить
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog :visible.sync="dialogVisible" :title="edit ? 'Редактирование' : 'Добавить'">
      <el-form :model="rating" label-width="150px" label-position="left">
        <el-form-item label="Название">
          <el-input v-model="rating.name" placeholder="Название" />
        </el-form-item>
        <el-form-item label="что оплачиваем ">
          <el-input v-model="rating.discription" placeholder="Оплата" />
        </el-form-item>
        <el-form-item v-if="type.depends == 1" label="Тариф на 1 сотку">
          <el-input v-model="rating.rate.ratio_a" placeholder="Оплата" />
        </el-form-item>
        <el-form-item  v-if="type.depends === 1" label="Тариф на 1 участок">
          <el-input v-model="rating.rate.ratio_b" placeholder="Оплата" />
        </el-form-item>
        <el-form-item  v-if="type.depends == 2" :label="rateLabel">
          <el-input v-model="rating.rate.ratio_a" placeholder="Оплата" />
        </el-form-item>
        <el-form-item label="Описание оплаты">
          <el-input v-model="rate_disc" placeholder="Оплата" readonly/>
        </el-form-item>
        <el-form-item label="Статус">
          <el-checkbox
            v-model="rating.enable"
            label="Действующий"
            :true-label=1
            :false-label=0
            border
          ></el-checkbox>
        </el-form-item>
      </el-form>
      <div style="text-align:right;">
        <el-button type="danger" @click="dialogVisible=false">Отмена</el-button>
        <el-button v-if="edit" type="primary" @click="confirmRate">Сохранить</el-button>
        <el-button v-if="!edit" type="primary" @click="confirmAddRate">Добавить</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, updateRate, createRate } from '@/api/admin/setting/rate'
import waves from '@/directive/waves'
import EditSetting from './EditSetting.vue'
export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'danger'
      }
      return statusMap[status]
    }
  },
  components: { EditSetting },
  directives: { waves },
  props: {
    type: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      showSettings: false,
      list: null,
      listQuery: {
        page: 1,
        limit: 5,
        type: this.type.id,
        sort: '+id'
      },
      dialogVisible: false,
      rating: {
        name: '',
        rate: {}

      },
      edit: true,
      loading: false
    }
  },
  computed: {
    rateLabel() {
      return 'Тариф на 1 ' + this.type.options.unit_name
    },
    rate_disc() {
      if (this.type.depends === 2) {
        return this.rating.rate.ratio_a + ' руб*' + this.type.options.unit_name
      }
      if (this.type.depends === 1) {
        let text = ''
        if (this.rating.rate.ratio_a > 0) {
          text += this.rating.rate.ratio_a + ' руб с сотки'
        }
        if (this.rating.rate.ratio_b > 0) {
          if (this.rating.rate.ratio_a > 0) {
            text += ' и '
          }
          text += this.rating.rate.ratio_b + ' руб с участка'
        }
        return text
      }
      return ''
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    addRateType() {
      this.edit = false
      this.rating = {
        name: '',
        rate: {}
      }
      this.dialogVisible = true
    },
    getList() {
      this.loading = true
      this.$emit('create') // for test
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.loading = false
      })
    },
    editRating(row) {
      this.edit = true
      this.rating = row
      this.dialogVisible = true
    },
    confirmAddRate() {
      this.rating.rate.discription = this.rate_disc
      this.rating.type_id = this.type.id
      createRate(this.rating)
        .then(response => {
          if (response.data.status) {
            this.$message.success('Данные успешно сохранены')
            this.getList()
            this.dialogVisible = false
          } else {
            this.$message({
              message: 'Ошибка при сохранении',
              type: 'Warning'
            })
          }
        })
    },
    confirmRate() {
      this.rating.rate.discription = this.rate_disc
      updateRate(this.rating.id, this.rating)
        .then(response => {
          if (response.data.status) {
            this.$message.success('Данные успешно сохранены')
            this.getList()
            this.dialogVisible = false
          } else {
            this.$message({
              message: 'Ошибка при сохранении',
              type: 'Warning'
            })
          }
        })
    }
  }
}
</script>

