<template>
  <div class="app-container">
    <div style="display: inline-block;">
      <el-button type="primary">Добавить выписку из банка</el-button>
    </div>
    <div class="mt4">
      <el-table
        :data="list"
        border
        style="width: 100%"
      >
        <el-table-column
          label="№ выписки"
          width="180"
        >
          <template slot-scope="{row}">
            {{ row.id }}
          </template>
        </el-table-column>
        <el-table-column
          label="Дата загрузки"
          width="180"
        >
          <template slot-scope="{row}">
            {{ row.created_at }}
          </template>
        </el-table-column>
        <el-table-column
          label="Имя файла"
        >
          <template slot-scope="{row}">
            {{ row.file_name }}
          </template>
        </el-table-column>
        <el-table-column
          label="Кол-во платежей"
        >
          <template slot-scope="{row}">
            <span v-if="row.data.data">
              {{ row.data.data.length }}
            </span>
          </template>
        </el-table-column>
        <el-table-column
          label=""
        >
          <template slot-scope="{row}">
            <span v-if="row.data.data">
              <el-button type="primary" size="small" @click="show(row.id)">Смотреть</el-button>
            </span>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
<!--  <BilligBankReestr :is-edit="false" />-->
</template>

<script>
// import BilligBankReestr from './components/BillingBankReestrDetail.vue'
import { fetchBillingBankReestrList } from '@/api/admin/billing'
export default {
  components:
    {
      // BilligBankReestr
    },
  data() {
    return {
      token: { 'X-XSRF-TOKEN': this.getCookie('XSRF-TOKEN') },
      list: [],
      showl: 0,
      listQuery: {
        page: 1,
        limit: 20,
        find: null,
        status: null,
        category: null
      }
    }
  },
  computed: {
    url() {
      return process.env.VUE_APP_BASE_API + '/api/v1/admin/billing/bank-reestr/upload'
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    show(id) {
      this.$router.push('/admin/bookkeping/billing_bank_reestr/' + id)
    },
    getList() {
      fetchBillingBankReestrList(this.listQuery).then(response => {
        if (response.data.status) {
          this.list = response.data.data.data
        }
      })
    },
    handleSuccess(response, file, fileList) {
      if (response.status) {
        this.$router.push('/admin/bookkeping/billing_bank_reestr/' + response.data.id)
      } else {
        this.$message.error(response.data)
      }
      console.log(response)
    },
    handleExceed() {
    },
    getCookie(name) {
      const matches = document.cookie.match(new RegExp(
        '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'
      ))
      return matches ? decodeURIComponent(matches[1]) : undefined
    },
    submitUpload() {
      this.$refs.upload.submit()
    }
  }
}
</script>

