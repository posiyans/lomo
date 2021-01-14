<template>
  <div>
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
      <el-table-column min-width="300px" label="Название">
        <template slot-scope="{row}">
          <span>{{ row.name }}</span>
          <el-tag>{{ row.discription }}</el-tag>
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
    <el-dialog :visible.sync="dialogVisible" title="Редактирование">
      <el-form :model="rating" label-width="150px" label-position="left">
        <el-form-item label="Тип взноса">
          <el-select v-model="rating.type_id" placeholder="Select">
            <el-option
              v-for="item in tabMapOptions"
              :key="item.key"
              :label="item.label"
              :value="item.key"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Название">
          <el-input v-model="rating.name" placeholder="Название" />
        </el-form-item>
        <el-form-item label="что оплачиваем ">
          <el-input v-model="rating.discription" placeholder="Оплата" />
        </el-form-item>
        <el-form-item v-if="rating.type_id == 2" label="Тариф на 1 сотку">
          <el-input v-model="rating.rate.ratio_a" placeholder="Оплата" />
        </el-form-item>
        <el-form-item v-if="rating.type_id == 2" label="Тариф на 1 участок">
          <el-input v-model="rating.rate.ratio_b" placeholder="Оплата" />
        </el-form-item>

        <el-form-item v-if="rating.type_id == 1" label="Тариф на 1 кВт/ч">
          <el-input v-model="rating.rate.ratio_a" placeholder="Оплата" />
        </el-form-item>
        <el-form-item label="Описание оплаты">
          <el-input v-model="rate_disc" placeholder="Оплата" readonly />
        </el-form-item>
        <el-form-item label="Статус">
          <el-checkbox
            v-model="rating.enable"
            label="Действующий"
            :true-label="1"
            :false-label="0"
            border
          />
        </el-form-item>
      </el-form>
      <div style="text-align:right;">
        <el-button type="danger" @click="dialogVisible=false">Отмена</el-button>
        <el-button type="primary" @click="confirmRate">Сохранить</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, updateRate } from '@/api/admin/setting/rate'

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
  props: {
    type: {
      type: String,
      default: '1'
    }
  },
  data() {
    return {
      tabMapOptions: [
        { label: 'Коммунальные', key: '1' },
        { label: 'Взносы', key: '2' }
      ],
      list: null,
      listQuery: {
        page: 1,
        limit: 5,
        type: this.type,
        sort: '+id'
      },
      dialogVisible: false,
      rating: {
        name: '',
        rate: {}

      },
      loading: false
    }
  },
  computed: {
    rate_disc() {
      if (this.rating.type_id == 1) {
        return this.rating.rate.ratio_a + ' руб*кВт/ч'
      }
      if (this.rating.type_id == 2) {
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
    getList() {
      this.loading = true
      this.$emit('create') // for test
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.loading = false
      })
    },
    editRating(row) {
      this.rating = row
      this.dialogVisible = true
    },
    confirmRate() {
      this.rating.rate.discription = this.rate_disc
      updateRate(this.rating)
        .then(response => {
          if (response.data.status) {
            this.getList()
            this.dialogVisible = false
          }
          this.dialogVisible = false
          this.$message({
            message: 'Ошибка при сохранении',
            type: 'Warning'
          })
        })
    }
  }
}
</script>

