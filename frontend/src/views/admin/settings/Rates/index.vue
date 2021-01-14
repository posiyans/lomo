<template>
  <div class="app-container">
    <div class="filter-container">
      <div class="filter-item">
        <el-tag>Тарифы</el-tag>
      </div>
      <el-button v-waves class="filter-item" size="small" type="primary" icon="el-icon-circle-plus-outline" @click="addReceipt">
        Добавить
      </el-button>
    </div>

    <el-tabs
      v-model="activeName"
      v-loading="loading"
      style="margin-top:15px;"
      type="border-card"
    >
      <el-tab-pane v-for="item in type" :key="item.name" :label="item.name" :name="'' + item.id">
        <keep-alive>
          <tab-pane v-if="activeName==item.id" :type="item" @create="showCreatedTimes" />
        </keep-alive>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import tabPane from './components/TabPane'
import { fetchReceiptTypeList, createReceiptType } from '@/api/admin/setting/receipt'
import waves from '@/directive/waves'

export default {
  name: 'Tab',
  components: { tabPane },
  directives: { waves },
  data() {
    return {
      tabMapOptions: [
        { label: 'Коммунальные', key: '1' },
        { label: 'Взносы', key: '2' }
      ],
      activeName: '1',
      createdTimes: 0,
      type: [],
      loading: true
    }
  },
  watch: {
    activeName(val) {
      this.$router.push(`${this.$route.path}?tab=${val}`)
    }
  },
  mounted() {
    // init the default selected tab
    this.getList()
  },
  methods: {
    addReceipt() {
      this.$prompt('Введите название типа платежа', 'Добавить', {
        confirmButtonText: 'Добавить',
        cancelButtonText: 'Отмена',
      }).then(({ value }) => {
        createReceiptType({ name: value })
          .then(response => {
            if (response.data.status) {
              this.$message('Данные успешно сохранены')
              this.getList()
            } else {
              if (response.data.data) {
                this.$message.error(response.data.data)
              }
            }
          })

      }).catch(() => {

      })
    },
    showCreatedTimes() {
      this.createdTimes = this.createdTimes + 1
    },
    getList() {
      this.loading = true
      fetchReceiptTypeList()
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            this.type = response.data.data
            const tab = this.$route.query.tab
            if (tab) {
              this.activeName = '' + tab
            }
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
          this.loading = false
        })
    }
  }
}
</script>

<style scoped>
  .tab-container {
    margin: 30px;
  }
</style>

